<?php

namespace App\Services\Payment;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class EsewaPaymentStateService
{
    public function __construct(
        private readonly TrekPaymentService $trekPaymentService,
    ) {
    }

    public function markCheckoutCompleted(Payment $payment, array $gatewayPayload = []): Payment
    {
        DB::transaction(function () use ($payment, $gatewayPayload) {
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
}
