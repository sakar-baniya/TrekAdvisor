<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Services\StripeCheckoutService;
use Stripe\Event;


/**
 * Yo StripeCheckoutWorkflowService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class StripeCheckoutWorkflowService
{
    public function __construct(
        protected TrekPaymentService $trekPaymentService,
        protected \App\Services\StripeCheckoutService $stripeCheckoutService,
        protected StripePaymentStateService $stripePaymentStateService
    ) {}

    /**
     * Yo method le failed/pending payment retry flow ko naya checkout link/session banaucha.
     *
     * Why:
     * Write workflow ko validation ra status change ekai thau ma rakhda data mismatch ra side-effect bug kam hunchha.
     */
    public function createRetrySession(Payment $payment): string
    {
        $booking = $this->trekPaymentService->getCheckoutBooking($payment);

        $session = $this->stripeCheckoutService->createTrekCheckoutSession(
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
     * Yo method le success callback pachi payment ra booking state sync garcha.
     *
     * Why:
     * Yo method ko business rule service layer ma rakhda future change garna ra test garna sajilo hunchha.
     */
    public function syncSuccessfulPayment(Payment $payment, string $sessionId): Payment
    {
        if ($sessionId === '') {
            return $payment->fresh() ?? $payment;
        }

        $session = $this->stripeCheckoutService->retrieveSession($sessionId);

        if ($session->payment_status !== 'paid') {
            return $payment->fresh() ?? $payment;
        }

        return $this->stripePaymentStateService->markCheckoutCompleted(
            $session->id,
            is_string($session->payment_intent) ? $session->payment_intent : null,
            $session->toArray()
        ) ?? ($payment->fresh() ?? $payment);
    }

    /**
     * Yo method le createWebhookEvent related business flow execute garcha.
     *
     * Why:
     * Write workflow ko validation ra status change ekai thau ma rakhda data mismatch ra side-effect bug kam hunchha.
     */
    public function createWebhookEvent(string $payload, string $signature): Event
    {
        return $this->stripeCheckoutService->constructWebhookEvent($payload, $signature);
    }

    /**
     * Yo method le gateway webhook event parse garera payment state update trigger garcha.
     *
     * Why:
     * Yo method ko business rule service layer ma rakhda future change garna ra test garna sajilo hunchha.
     */
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






