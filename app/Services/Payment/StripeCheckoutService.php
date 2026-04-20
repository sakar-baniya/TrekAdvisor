<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\TrekBooking;
use Illuminate\Support\Facades\DB;
use Stripe\Checkout\Session;
use Stripe\Event;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Stripe\Webhook;

/**
 * Consolidated Stripe Checkout Service: Handles API interactions, workflows, and state updates.
 */
class StripeCheckoutService
{
    public function __construct(
        protected TrekPaymentService $trekPaymentService
    ) {}

    /**
     * Get the Stripe client instance.
     */
    public function client(): StripeClient
    {
        $secret = config('services.stripe.secret');

        if (! $secret) {
            throw new \RuntimeException('Stripe secret key is not configured.');
        }

        return new StripeClient($secret);
    }

    /**
     * Create a Stripe Checkout Session for a Trek booking.
     */
    public function createTrekCheckoutSession(Payment $payment, TrekBooking $booking, string $successUrl, string $cancelUrl): Session
    {
        $booking->loadMissing('departure.trek', 'user');

        return $this->client()->checkout->sessions->create([
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'payment_method_types' => ['card'],
            'customer_email' => $payment->user?->email,
            'metadata' => [
                'payment_id' => (string) $payment->id,
                'booking_id' => (string) $booking->id,
                'booking_reference' => $booking->booking_reference,
                'payable_type' => $payment->payable_type,
            ],
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => strtolower($payment->currency ?: 'NPR'),
                    'unit_amount' => (int) round(((float) $payment->amount) * 100),
                    'product_data' => [
                        'name' => $booking->departure?->trek?->title ?? 'Trek Booking',
                        'description' => 'Reference ' . $booking->booking_reference . ' for ' . $booking->total_passengers . ' passenger(s)',
                    ],
                ],
            ]],
        ]);
    }

    /**
     * Retrieve a Stripe Checkout Session.
     */
    public function retrieveSession(string $sessionId): Session
    {
        return $this->client()->checkout->sessions->retrieve($sessionId, []);
    }

    /**
     * Construct a Stripe Webhook Event.
     */
    public function constructWebhookEvent(string $payload, string $signature): Event
    {
        $secret = config('services.stripe.webhook_secret');

        if (! $secret) {
            throw new \RuntimeException('Stripe webhook secret is not configured.');
        }

        return Webhook::constructEvent($payload, $signature, $secret);
    }

    /**
     * Create a retry session for a failed/pending payment.
     */
    public function createRetrySession(Payment $payment): string
    {
        $booking = $this->trekPaymentService->getCheckoutBooking($payment);

        $session = $this->createTrekCheckoutSession(
            $payment,
            $booking,
            route('stripe.success', ['payment' => $payment]) . '?session_id={CHECKOUT_SESSION_ID}',
            route('stripe.cancel', ['payment' => $payment])
        );

        $gatewayResponse = ['checkout_session_created' => $session->id];
        if ($session->url !== null) {
            $gatewayResponse['checkout_url'] = $session->url;
        }

        $payment->forceFill([
            'gateway' => 'stripe',
            'stripe_session_id' => $session->id,
            'gateway_response' => json_encode($gatewayResponse),
        ])->save();

        return $session->url;
    }

    /**
     * Sync payment status from session ID.
     */
    public function syncSuccessfulPayment(Payment $payment, string $sessionId): Payment
    {
        if ($sessionId === '') {
            return $payment->fresh() ?? $payment;
        }

        $session = $this->retrieveSession($sessionId);

        if ($session->payment_status !== 'paid') {
            return $payment->fresh() ?? $payment;
        }

        return $this->markCheckoutCompleted(
            $session->id,
            is_string($session->payment_intent) ? $session->payment_intent : null,
            $session->toArray()
        ) ?? ($payment->fresh() ?? $payment);
    }

    /**
     * Handle Stripe Webhook Events.
     */
    public function handleWebhookEvent(Event $event): void
    {
        $object = $event->data->object;

        if (! isset($object->id) || ! is_string($object->id)) {
            return;
        }

        if ($event->type === 'checkout.session.completed' || $event->type === 'checkout.session.async_payment_succeeded') {
            $this->markCheckoutCompleted(
                $object->id,
                is_string($object->payment_intent ?? null) ? $object->payment_intent : null,
                (array) $object
            );
        }

        if ($event->type === 'checkout.session.expired' || $event->type === 'checkout.session.async_payment_failed') {
            $this->markCheckoutFailed($object->id, (array) $object);
        }
    }

    /**
     * Internal: Mark payment as completed.
     */
    public function markCheckoutCompleted(string $sessionId, ?string $paymentIntentId = null, array $sessionPayload = []): ?Payment
    {
        $payment = Payment::query()->where('stripe_session_id', $sessionId)->first();

        if (! $payment) {
            return null;
        }

        DB::transaction(function () use ($payment, $paymentIntentId, $sessionPayload) {
            $payment = $payment->fresh() ?? $payment;

            if ($payment->status !== 'success') {
                $payment->status = 'success';
                $payment->paid_at = now();
            }

            if ($paymentIntentId) {
                $payment->stripe_payment_intent_id = $paymentIntentId;
            }

            if ($sessionPayload !== []) {
                $payment->gateway_response = json_encode($sessionPayload);
            }

            $payment->gateway = 'stripe';
            $payment->save();
            $this->trekPaymentService->confirmBooking($payment);
        });

        return $payment->fresh() ?? $payment;
    }

    /**
     * Internal: Mark payment as failed or cancelled.
     */
    public function markCheckoutFailed(string $sessionId, array $sessionPayload = []): ?Payment
    {
        $payment = Payment::query()->where('stripe_session_id', $sessionId)->first();

        if (! $payment) {
            return null;
        }

        if ($payment->status === 'success') {
            return $payment;
        }

        $payment->forceFill([
            'status' => 'failed',
            'gateway' => 'stripe',
            'gateway_response' => $sessionPayload !== [] ? json_encode($sessionPayload) : $payment->gateway_response,
        ])->save();

        return $payment;
    }
}
