<?php

namespace App\Policies;

use App\Models\HotelBooking;
use App\Models\User;

class HotelBookingPolicy
{
    /**
     * ADMIN: Can view all hotel bookings
     * STAFF: Can view all hotel bookings
     * CUSTOMER: Can view own bookings
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'hotel_owner', 'customer']);
    }

    /**
     * ADMIN: Can view any booking
     * STAFF: Can view any booking
     * CUSTOMER: Can view own booking
     */
    public function view(User $user, HotelBooking $booking): bool
    {
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }

        if ($user->role === 'hotel_owner') {
            return (int) ($booking->hotelRoom?->hotel?->owner_id ?? 0) === (int) $user->id;
        }

        if ($user->role === 'customer') {
            return $user->id === $booking->user_id;
        }

        return false;
    }

    /**
     * ADMIN: Can create bookings
     * STAFF: CANNOT create (customers book themselves)
     * CUSTOMER: Can create bookings
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
    public function update(User $user, HotelBooking $booking): bool
    {
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }

        if ($user->role === 'hotel_owner') {
            return (int) ($booking->hotelRoom?->hotel?->owner_id ?? 0) === (int) $user->id;
        }

        return false;
    }

    /**
     * ADMIN: Can delete bookings
     * STAFF: CANNOT delete
     * CUSTOMER: Cannot delete
     */
    public function delete(User $user, HotelBooking $booking): bool
    {
        return $user->role === 'admin';
    }
}
