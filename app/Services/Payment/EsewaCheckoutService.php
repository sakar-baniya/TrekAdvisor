<?php

namespace App\Services\Payment;

use App\Models\Payment;
use RuntimeException;
use Illuminate\Support\Facades\Http;

class EsewaCheckoutService
{
	public function checkoutUrl(): string
	{
		return (string) config('services.esewa.checkout_url');
	}

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

	public function createCheckoutPayload(Payment $payment, string $successUrl, string $failureUrl): array
	{
		$productCode = (string) config('services.esewa.product_code');
		$secretKey = (string) config('services.esewa.secret_key');

		if ($productCode === '' || $secretKey === '') {
			throw new RuntimeException('eSewa credentials are not configured.');
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

		$response = Http::timeout(10)->get($statusUrl, $query);

		if (! $response->ok()) {
			return false;
		}

		$status = strtoupper((string) $response->json('status', ''));

		return $status === 'COMPLETE';
	}
}
