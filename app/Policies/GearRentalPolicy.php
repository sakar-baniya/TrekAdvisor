<?php

namespace App\Policies;

use App\Models\GearRental;
use App\Models\User;

class GearRentalPolicy
{
    /**
     * ADMIN: Can view all gear rentals
     * STAFF: Can view all gear rentals (operations)
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * ADMIN: Can view any rental
     * STAFF: Can view any rental
     */
    public function view(User $user, GearRental $rental): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * ADMIN: Can manage rentals (update status, assign, etc)
     * STAFF: Can update rental status (pickup confirmation, return, etc)
     */
    public function update(User $user, GearRental $rental): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * ADMIN: Can mark gear returned
     * STAFF: Can mark gear returned
     */
    public function markReturned(User $user, GearRental $rental): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * ADMIN: Can confirm pickup
     * STAFF: Can confirm pickup
     */
    public function confirmPickup(User $user, GearRental $rental): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }
}
