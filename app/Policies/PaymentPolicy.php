<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    /**
     * ADMIN: Can view all payments
     * STAFF: Can view all payments (for verification/reporting)
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * ADMIN: Can view any payment
     * STAFF: Can view any payment (view-only)
     */
    public function view(User $user, Payment $payment): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * ADMIN: Can modify/refund payments
     * STAFF: CANNOT modify payments (view-only)
     */
    public function update(User $user, Payment $payment): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN: Can refund payments
     * STAFF: CANNOT refund
     */
    public function refund(User $user, Payment $payment): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN ONLY: Can access payment gateway settings
     * STAFF: CANNOT access gateway configuration
     */
    public function accessGateway(User $user): bool
    {
        return $user->role === 'admin';
    }
}
