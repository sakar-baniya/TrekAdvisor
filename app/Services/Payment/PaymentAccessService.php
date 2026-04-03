<?php

namespace App\Services\Payment;

use App\Models\Payment;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PaymentAccessService
{
    public function authorizeOwner(Payment $payment, int $userId): void
    {
        if ((int) $payment->user_id !== $userId) {
            throw new AccessDeniedHttpException();
        }
    }
}
