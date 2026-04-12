<?php

namespace App\Services\Payment;

use App\Models\Payment;

class EsewaCheckoutWorkflowService
{
    public function __construct(
        private readonly EsewaCheckoutService $esewaCheckoutService,
        private readonly EsewaPaymentStateService $esewaPaymentStateService,
    ) {
    }

    public function createRetryUrl(Payment $payment): string
    {
        return route('esewa.retry', ['payment' => $payment]);
    }

    public function buildRedirectData(Payment $payment): array
    {
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

    public function markFailed(Payment $payment, array $requestPayload = []): Payment
    {
        return $this->esewaPaymentStateService->markCheckoutFailed($payment, $requestPayload);
    }
}
