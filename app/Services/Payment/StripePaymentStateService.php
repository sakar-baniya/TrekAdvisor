<?php

namespace App\Services\Payment;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;


/**
 * Yo StripePaymentStateService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class StripePaymentStateService
{
    public function __construct(
        protected TrekPaymentService $trekPaymentService
    ) {}

    /**
     * Yo method le markCheckoutCompleted related state change safely apply garcha.
     *
     * Why:
     * Write workflow ko validation ra status change ekai thau ma rakhda data mismatch ra side-effect bug kam hunchha.
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
     * Yo method le markCheckoutFailed related state change safely apply garcha.
     *
     * Why:
     * Write workflow ko validation ra status change ekai thau ma rakhda data mismatch ra side-effect bug kam hunchha.
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





