<?php

namespace App\Repositories\Contracts;

use App\Models\Payment;

interface PaymentRepositoryInterface
{
    public function create(array $attributes): Payment;

    public function findByStripeSessionId(string $sessionId): ?Payment;

    public function saveCheckoutSession(Payment $payment, string $sessionId, ?string $checkoutUrl = null): Payment;

    public function markCheckoutCompleted(Payment $payment, ?string $paymentIntentId = null, array $sessionPayload = []): Payment;

    public function markCheckoutFailed(Payment $payment, array $sessionPayload = []): Payment;

    public function refresh(Payment $payment): Payment;
}
