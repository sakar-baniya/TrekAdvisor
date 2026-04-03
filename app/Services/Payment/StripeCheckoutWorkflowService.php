<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Services\StripeCheckoutService;
use Stripe\Event;

class StripeCheckoutWorkflowService
{
    public function __construct(
        private readonly StripeCheckoutService $stripeCheckoutService,
        private readonly StripePaymentStateService $stripePaymentStateService,
        private readonly TrekPaymentService $trekPaymentService,
        private readonly PaymentRepositoryInterface $payments,
    ) {
    }

    public function createRetrySession(Payment $payment): string
    {
        $booking = $this->trekPaymentService->getCheckoutBooking($payment);

        $session = $this->stripeCheckoutService->createTrekCheckoutSession(
            $payment,
            $booking,
            route('stripe.success', ['payment' => $payment]) . '?session_id={CHECKOUT_SESSION_ID}',
            route('stripe.cancel', ['payment' => $payment])
        );

        $this->payments->saveCheckoutSession($payment, $session->id, $session->url);

        return $session->url;
    }

    public function syncSuccessfulPayment(Payment $payment, string $sessionId): Payment
    {
        if ($sessionId === '') {
            return $this->payments->refresh($payment);
        }

        $session = $this->stripeCheckoutService->retrieveSession($sessionId);

        if ($session->payment_status !== 'paid') {
            return $this->payments->refresh($payment);
        }

        return $this->stripePaymentStateService->markCheckoutCompleted(
            $session->id,
            is_string($session->payment_intent) ? $session->payment_intent : null,
            $session->toArray()
        ) ?? $this->payments->refresh($payment);
    }

    public function createWebhookEvent(string $payload, string $signature): Event
    {
        return $this->stripeCheckoutService->constructWebhookEvent($payload, $signature);
    }

    public function handleWebhookEvent(Event $event): void
    {
        $object = $event->data->object;

        if (! isset($object->id) || ! is_string($object->id)) {
            return;
        }

        if ($event->type === 'checkout.session.completed' || $event->type === 'checkout.session.async_payment_succeeded') {
            $this->stripePaymentStateService->markCheckoutCompleted(
                $object->id,
                is_string($object->payment_intent ?? null) ? $object->payment_intent : null,
                (array) $object
            );
        }

        if ($event->type === 'checkout.session.expired' || $event->type === 'checkout.session.async_payment_failed') {
            $this->stripePaymentStateService->markCheckoutFailed($object->id, (array) $object);
        }
    }
}
