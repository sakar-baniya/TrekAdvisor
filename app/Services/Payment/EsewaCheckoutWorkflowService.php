<?php

namespace App\Services\Payment;

use App\Models\Payment;


/**
 * Yo EsewaCheckoutWorkflowService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class EsewaCheckoutWorkflowService
{
    public function __construct(
        protected EsewaCheckoutService $esewaCheckoutService,
        protected EsewaPaymentStateService $esewaPaymentStateService
    ) {}

    /**
     * Yo method le failed/pending payment retry flow ko naya checkout link/session banaucha.
     *
     * Why:
     * Write workflow ko validation ra status change ekai thau ma rakhda data mismatch ra side-effect bug kam hunchha.
     */
    public function createRetryUrl(Payment $payment): string
    {
        return route('esewa.retry', ['payment' => $payment]);
    }

    /**
     * Yo method le buildRedirectData ko service-level kaam handle garcha.
     *
     * Why:
     * Output banne rule yahi method ma clear rakhda format change huda impact track garna sajilo hunchha.
     */
    public function buildRedirectData(Payment $payment): array
    {
        // Refresh transaction ID to prevent 'Duplicate transaction UUID' error on eSewa retries
        $payment->transaction_id = 'TXN-' . strtoupper(\Illuminate\Support\Str::random(12));

        $payload = $this->esewaCheckoutService->createCheckoutPayload(
            $payment,
            route('esewa.success', ['payment' => $payment]),
            route('esewa.failure', ['payment' => $payment])
        );

        $payment->forceFill([
            'gateway' => 'esewa',
            'gateway_response' => json_encode([
                'checkout_initialized' => true,
                'transaction_uuid' => $payment->transaction_id,
            ]),
        ])->save();

        return [
            'endpoint' => $this->esewaCheckoutService->checkoutUrl(),
            'payload' => $payload,
        ];
    }

    /**
     * Yo method le success callback pachi payment ra booking state sync garcha.
     *
     * Why:
     * Yo method ko business rule service layer ma rakhda future change garna ra test garna sajilo hunchha.
     */
    public function syncSuccessfulPayment(Payment $payment, array $requestPayload): Payment
    {
        $payload = $this->esewaCheckoutService->decodeSuccessPayload(
            (string) ($requestPayload['data'] ?? ''),
            $requestPayload
        );

        if ($payload === [] || ! $this->esewaCheckoutService->verifyPayloadSignature($payload)) {
            return $this->esewaPaymentStateService->markCheckoutFailed($payment, [
                'reason' => 'invalid_signature_or_payload',
                'request' => $requestPayload,
            ]);
        }

        $status = strtoupper((string) ($payload['status'] ?? ''));
        if ($status !== 'COMPLETE') {
            return $this->esewaPaymentStateService->markCheckoutFailed($payment, $payload);
        }

        $transactionCode = (string) ($payload['transaction_code'] ?? '');

        if (! $this->esewaCheckoutService->verifyTransaction($payment, $transactionCode)) {
            return $this->esewaPaymentStateService->markCheckoutFailed($payment, [
                'reason' => 'status_check_failed',
                'payload' => $payload,
            ]);
        }

        return $this->esewaPaymentStateService->markCheckoutCompleted($payment, $payload);
    }

    /**
     * Yo method le failed callback pachi payment status failed/cancelled ma update garcha.
     *
     * Why:
     * Write workflow ko validation ra status change ekai thau ma rakhda data mismatch ra side-effect bug kam hunchha.
     */
    public function markFailed(Payment $payment, array $requestPayload = []): Payment
    {
        return $this->esewaPaymentStateService->markCheckoutFailed($payment, $requestPayload);
    }
}





