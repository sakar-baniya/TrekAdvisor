<?php

namespace App\Services\Payment;

use App\Models\Payment;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


/**
 * Yo PaymentAccessService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class PaymentAccessService
{
    /**
     * Yo method le current user le yo payment access garna milcha ki mildaina bhanne check garcha.
     *
     * Why:
     * Yo method ko business rule service layer ma rakhda future change garna ra test garna sajilo hunchha.
     */
    public function authorizeOwner(Payment $payment, int $userId): void
    {
        if ((int) $payment->user_id !== $userId) {
            throw new AccessDeniedHttpException();
        }
    }
}





