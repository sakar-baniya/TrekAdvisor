<?php

namespace App\Repositories\Eloquent;

use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;

class EloquentPaymentRepository implements PaymentRepositoryInterface
{
    public function create(array $attributes): Payment
    {
        return Payment::query()->create($attributes);
    }

    public function findByStripeSessionId(string $sessionId): ?Payment
    {
        return Payment::query()
            ->where('stripe_session_id', $sessionId)
            ->first();
    }

    public function saveCheckoutSession(Payment $payment, string $sessionId, ?string $checkoutUrl = null): Payment
    {
        $gatewayResponse = [
            'checkout_session_created' => $sessionId,
        ];

        if ($checkoutUrl !== null) {
            $gatewayResponse['checkout_url'] = $checkoutUrl;
        }

        $payment->forceFill([
            'gateway' => 'stripe',
            'stripe_session_id' => $sessionId,
            'gateway_response' => json_encode($gatewayResponse),
        ])->save();

        return $payment;
    }

    public function markCheckoutCompleted(Payment $payment, ?string $paymentIntentId = null, array $sessionPayload = []): Payment
    {
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

        return $payment;
    }

    public function markCheckoutFailed(Payment $payment, array $sessionPayload = []): Payment
    {
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

    public function refresh(Payment $payment): Payment
    {
        return $payment->fresh() ?? $payment;
    }
}
