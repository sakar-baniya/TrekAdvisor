<?php

namespace App\Policies;

use App\Models\Hotel;
use App\Models\User;

class HotelPolicy
{
    /**
     * ADMIN: Can view all hotels
     * STAFF: Can view all hotels (operations)
     * HOTEL_OWNER: Can view own hotels
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'hotel_owner']);
    }

    /**
     * ADMIN: Can view any hotel
     * STAFF: Can view any hotel (operations)
     * HOTEL_OWNER: Can view own hotel
     */
    public function view(User $user, Hotel $hotel): bool
    {
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }

        if ($user->role === 'hotel_owner') {
            return $user->id === $hotel->owner_id;
        }

        return false;
    }

    /**
     * ADMIN: Can create hotels
     * STAFF: CANNOT create (admin creates all)
     * HOTEL_OWNER: Cannot create (admin assigns hotels)
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN: Can edit any hotel
     * STAFF: Can edit any hotel (operations)
     * HOTEL_OWNER: Can edit own hotel only
     */
    public function update(User $user, Hotel $hotel): bool
    {
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }

        if ($user->role === 'hotel_owner') {
            return $user->id === $hotel->owner_id;
        }

        return false;
    }

    /**
     * ADMIN: Can delete hotels
     * STAFF: CANNOT delete
     * HOTEL_OWNER: Cannot delete
     */
    public function delete(User $user, Hotel $hotel): bool
    {
        return $user->role === 'admin';
    }
}
