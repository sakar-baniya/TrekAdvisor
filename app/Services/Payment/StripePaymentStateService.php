<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Support\Facades\DB;

class StripePaymentStateService
{
    public function __construct(
        private readonly PaymentRepositoryInterface $payments,
        private readonly TrekPaymentService $trekPaymentService,
    ) {
    }

    public function markCheckoutCompleted(string $sessionId, ?string $paymentIntentId = null, array $sessionPayload = []): ?Payment
    {
        $payment = $this->payments->findByStripeSessionId($sessionId);

        if (! $payment) {
            return null;
        }

        DB::transaction(function () use ($payment, $paymentIntentId, $sessionPayload) {
            $payment = $this->payments->refresh($payment);
            $this->payments->markCheckoutCompleted($payment, $paymentIntentId, $sessionPayload);
            $this->trekPaymentService->confirmBooking($payment);
        });

        return $this->payments->refresh($payment);
    }

    public function markCheckoutFailed(string $sessionId, array $sessionPayload = []): ?Payment
    {
        $payment = $this->payments->findByStripeSessionId($sessionId);

        if (! $payment) {
            return null;
        }

        return $this->payments->markCheckoutFailed($payment, $sessionPayload);
    }
}
