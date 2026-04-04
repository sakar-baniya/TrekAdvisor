<?php

namespace App\Policies;

use App\Models\TrekBooking;
use App\Models\User;

class TrekBookingPolicy
{
    /**
     * ADMIN: Can view all bookings
     * STAFF: Can view all bookings
     * CUSTOMER: Can view own bookings
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'customer']);
    }

    /**
     * ADMIN: Can view any booking
     * STAFF: Can view any booking
     * CUSTOMER: Can view own booking only
     */
    public function view(User $user, TrekBooking $booking): bool
    {
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }

        if ($user->role === 'customer') {
            return $user->id === $booking->user_id;
        }

        return false;
    }

    /**
     * ADMIN: Can create bookings
     * STAFF: CANNOT create
     * CUSTOMER: Can create their own bookings
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'customer']);
    }

    /**
     * ADMIN: Can update all bookings
     * STAFF: Can update all bookings (status, details)
     * CUSTOMER: Cannot update
     */
    public function update(User $user, TrekBooking $booking): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * ADMIN: Can delete bookings
     * STAFF: CANNOT delete
     * CUSTOMER: Cannot delete
     */
    public function delete(User $user, TrekBooking $booking): bool
    {
        return $user->role === 'admin';
    }
}
