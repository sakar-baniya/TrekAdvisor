<?php

namespace App\Services\Payment;

use App\Models\Payment;
use RuntimeException;
use Illuminate\Support\Facades\Http;


/**
 * Yo EsewaCheckoutService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class EsewaCheckoutService
{
    public function __construct(
        protected TrekPaymentService $trekPaymentService
    ) {}

    /**
     * Get the active eSewa checkout endpoint.
     */
    public function checkoutUrl(): string
    {
        return (string) config('services.esewa.checkout_url');
    }

    /**
     * Decode the eSewa success data payload.
     */
    public function decodeSuccessPayload(string $encodedData, array $fallbackPayload = []): array
    {
        if ($encodedData !== '') {
            $decoded = base64_decode($encodedData, true);
            if ($decoded !== false) {
                $json = json_decode($decoded, true);
                if (is_array($json)) {
                    return $json;
                }
            }
        }

        return $fallbackPayload;
    }

    /**
     * Verify the hash signature from eSewa payload.
     */
    public function verifyPayloadSignature(array $payload): bool
    {
        $secretKey = (string) config('services.esewa.secret_key');
        $signedFieldNames = (string) ($payload['signed_field_names'] ?? '');
        $receivedSignature = (string) ($payload['signature'] ?? '');

        if ($secretKey === '' || $signedFieldNames === '' || $receivedSignature === '') {
            return false;
        }

        $message = [];
        foreach (explode(',', $signedFieldNames) as $field) {
            $field = trim($field);
            if ($field === '' || ! array_key_exists($field, $payload)) {
                return false;
            }
            $message[] = $field . '=' . $payload[$field];
        }

        $generated = base64_encode(hash_hmac('sha256', implode(',', $message), $secretKey, true));

        return hash_equals($generated, $receivedSignature);
    }

    /**
     * Create the signed payload for eSewa checkout.
     */
    public function createCheckoutPayload(Payment $payment, string $successUrl, string $failureUrl): array
    {
        $productCode = (string) config('services.esewa.product_code');
        $secretKey = (string) config('services.esewa.secret_key');

        if ($productCode === '' || $secretKey === '') {
            throw new \RuntimeException('eSewa credentials are not configured.');
        }

        $totalAmount = number_format((float) $payment->amount, 2, '.', '');
        $transactionUuid = $payment->transaction_id;

        $signatureString = sprintf(
            'total_amount=%s,transaction_uuid=%s,product_code=%s',
            $totalAmount,
            $transactionUuid,
            $productCode
        );

        $signature = base64_encode(hash_hmac('sha256', $signatureString, $secretKey, true));

        return [
            'amount' => $totalAmount,
            'tax_amount' => '0',
            'total_amount' => $totalAmount,
            'transaction_uuid' => $transactionUuid,
            'product_code' => $productCode,
            'product_service_charge' => '0',
            'product_delivery_charge' => '0',
            'success_url' => $successUrl,
            'failure_url' => $failureUrl,
            'signed_field_names' => 'total_amount,transaction_uuid,product_code',
            'signature' => $signature,
        ];
    }

    /**
     * Verify transaction with eSewa status endpoint.
     */
    public function verifyTransaction(Payment $payment, ?string $transactionCode = null): bool
    {
        $productCode = (string) config('services.esewa.product_code');
        $statusUrl = (string) config('services.esewa.status_url');
        $totalAmount = rtrim(rtrim(number_format((float) $payment->amount, 2, '.', ''), '0'), '.');
        if ($totalAmount === '') {
            $totalAmount = '0';
        }

        if ($productCode === '' || $statusUrl === '') {
            return false;
        }

        $query = [
            'product_code' => $productCode,
            'total_amount' => $totalAmount,
            'transaction_uuid' => $payment->transaction_id,
        ];

        if ($transactionCode !== null && $transactionCode !== '') {
            $query['transaction_code'] = $transactionCode;
        }

        $response = \Illuminate\Support\Facades\Http::timeout(10)->get($statusUrl, $query);

        if (! $response->ok()) {
            return false;
        }

        $status = strtoupper((string) $response->json('status', ''));

        return $status === 'COMPLETE';
    }

    /**
     * Build standard redirect payload for checkout.
     */
    public function buildRedirectData(Payment $payment): array
    {
        $payment->transaction_id = 'TXN-' . strtoupper(\Illuminate\Support\Str::random(12));

        $payload = $this->createCheckoutPayload(
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
            'endpoint' => $this->checkoutUrl(),
            'payload' => $payload,
        ];
    }

    /**
     * Sync payment status from gateway callback data.
     */
    public function syncSuccessfulPayment(Payment $payment, array $requestPayload): Payment
    {
        $payload = $this->decodeSuccessPayload(
            (string) ($requestPayload['data'] ?? ''),
            $requestPayload
        );

        if ($payload === [] || ! $this->verifyPayloadSignature($payload)) {
            return $this->markCheckoutFailed($payment, [
                'reason' => 'invalid_signature_or_payload',
                'request' => $requestPayload,
            ]);
        }

        $status = strtoupper((string) ($payload['status'] ?? ''));
        if ($status !== 'COMPLETE') {
            return $this->markCheckoutFailed($payment, $payload);
        }

        $transactionCode = (string) ($payload['transaction_code'] ?? '');

        if (! $this->verifyTransaction($payment, $transactionCode)) {
            return $this->markCheckoutFailed($payment, [
                'reason' => 'status_check_failed',
                'payload' => $payload,
            ]);
        }

        return $this->markCheckoutCompleted($payment, $payload);
    }

    /**
     * Internal: Mark payment as completed.
     */
    public function markCheckoutCompleted(Payment $payment, array $gatewayPayload = []): Payment
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($payment, $gatewayPayload) {
            $freshPayment = $payment->fresh() ?? $payment;

            if ($freshPayment->status !== 'success') {
                $freshPayment->status = 'success';
                $freshPayment->paid_at = now();
            }

            $freshPayment->gateway = 'esewa';
            if ($gatewayPayload !== []) {
                $freshPayment->gateway_response = json_encode($gatewayPayload);
            }

            $freshPayment->save();
            $this->trekPaymentService->confirmBooking($freshPayment);
        });

        return $payment->fresh() ?? $payment;
    }

    /**
     * Internal: Mark payment as failed or cancelled.
     */
    public function markCheckoutFailed(Payment $payment, array $gatewayPayload = []): Payment
    {
        if ($payment->status === 'success') {
            return $payment;
        }

        $payment->forceFill([
            'status' => 'failed',
            'gateway' => 'esewa',
            'gateway_response' => $gatewayPayload !== [] ? json_encode($gatewayPayload) : $payment->gateway_response,
        ])->save();

        return $payment->fresh() ?? $payment;
    }

    public function markFailed(Payment $payment, array $requestPayload = []): Payment
    {
        return $this->markCheckoutFailed($payment, $requestPayload);
    }
}






