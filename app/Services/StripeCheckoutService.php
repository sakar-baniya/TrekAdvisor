<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\TrekBooking;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class StripeCheckoutService
{
    public function client(): StripeClient
    {
        $secret = config('services.stripe.secret');

        if (!$secret) {
            throw new RuntimeException('Stripe secret key is not configured.');
        }

        return new StripeClient($secret);
    }

    /**
     * @throws ApiErrorException
     */
    public function createTrekCheckoutSession(Payment $payment, TrekBooking $booking, string $successUrl, string $cancelUrl): Session
    {
        $booking->loadMissing('departure.trek', 'user');

        $session = $this->client()->checkout->sessions->create([
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'payment_method_types' => ['card'],
            'customer_email' => $payment->user?->email,
            'metadata' => [
                'payment_id' => (string) $payment->id,
                'booking_id' => (string) $booking->id,
                'booking_reference' => $booking->booking_reference,
                'payment_for' => $payment->payment_for,
            ],
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => strtolower($payment->currency ?: 'USD'),
                    'unit_amount' => (int) round(((float) $payment->amount) * 100),
                    'product_data' => [
                        'name' => $booking->departure?->trek?->title ?? 'Trek Booking',
                        'description' => 'Reference ' . $booking->booking_reference . ' for ' . $booking->total_passengers . ' passenger(s)',
                    ],
                ],
            ]],
        ]);

        $payment->forceFill([
            'gateway' => 'stripe',
            'stripe_session_id' => $session->id,
            'gateway_response' => json_encode([
                'checkout_session_created' => $session->id,
                'checkout_url' => $session->url,
            ]),
        ])->save();

        return $session;
    }

    /**
     * @throws ApiErrorException
     */
    public function retrieveSession(string $sessionId): Session
    {
        return $this->client()->checkout->sessions->retrieve($sessionId, []);
    }

    public function markCheckoutCompleted(string $sessionId, ?string $paymentIntentId = null, array $sessionPayload = []): ?Payment
    {
        $payment = Payment::query()
            ->where('stripe_session_id', $sessionId)
            ->first();

        if (!$payment) {
            return null;
        }

        DB::transaction(function () use ($payment, $paymentIntentId, $sessionPayload) {
            $payment->refresh();

            if ($payment->status !== 'Success') {
                $payment->status = 'Success';
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

            if ($payment->payment_for === 'trek') {
                $booking = TrekBooking::query()
                    ->with('departure')
                    ->find($payment->reference_id);

                if ($booking && $booking->status !== 'Confirmed') {
                    $booking->status = 'Confirmed';
                    $booking->save();

                    if ($booking->departure) {
                        $booking->departure->increment('booked_seats', $booking->total_passengers);
                    }
                }
            }
        });

        return $payment->fresh();
    }

    public function markCheckoutFailed(string $sessionId, array $sessionPayload = []): ?Payment
    {
        $payment = Payment::query()
            ->where('stripe_session_id', $sessionId)
            ->first();

        if (!$payment) {
            return null;
        }

        if ($payment->status === 'Success') {
            return $payment;
        }

        $payment->forceFill([
            'status' => 'Failed',
            'gateway' => 'stripe',
            'gateway_response' => $sessionPayload !== [] ? json_encode($sessionPayload) : $payment->gateway_response,
        ])->save();

        return $payment;
    }
}
