<?php

namespace App\Policies;

use App\Models\TrekBooking;
use App\Models\User;

class TrekBookingPolicy
{
    /**
     * ADMIN & STAFF: Sabai bookings herna pauchha (Can view all bookings)
     * CUSTOMER: Aafno bookings matra herna pauchha (Can view own bookings)
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'customer']);
    }

    /**
     * ADMIN & STAFF: Jo sukai ko booking herna pauchha (Can view any booking)
     * CUSTOMER: Aafno booking detail matra herna pauchha (Can view own booking only)
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
     * ADMIN: Booking banauna pauchha (Can create bookings)
     * STAFF: Booking banauna mildaina (CANNOT create)
     * CUSTOMER: Aafno booking aafai banauna pauchha (Can create their own bookings)
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'customer']);
    }

    /**
     * ADMIN & STAFF: Sabai booking update garna pauchha (Can update all bookings)
     * CUSTOMER: Update garna mildaina (Cannot update)
     */
    public function update(User $user, TrekBooking $booking): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Delete Booking: Kasle delete garna pauchha?
     * ADMIN: Delete garna pauchha (Can delete bookings)
     * STAFF & CUSTOMER: Delete garna mildaina (Cannot delete)
     */
    public function delete(User $user, TrekBooking $booking): bool
    {
        return $user->role === 'admin';
    }
}
