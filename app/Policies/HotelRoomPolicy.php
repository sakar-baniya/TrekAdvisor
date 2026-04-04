<?php

namespace App\Policies;

use App\Models\HotelRoom;
use App\Models\User;

class HotelRoomPolicy
{
    /**
     * ADMIN: Can view all hotel rooms
     * STAFF: Can view all hotel rooms (operations)
     * HOTEL_OWNER: Can view rooms in own hotel
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'hotel_owner']);
    }

    /**
     * ADMIN: Can view any room
     * STAFF: Can view any room
     * HOTEL_OWNER: Can view rooms in own hotel
     */
    public function view(User $user, HotelRoom $room): bool
    {
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }

        if ($user->role === 'hotel_owner') {
            return $user->id === $room->hotel->owner_id;
        }

        return false;
    }

    /**
     * ADMIN: Can create rooms
     * STAFF: CANNOT create (admin creates all)
     * HOTEL_OWNER: Cannot create
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN: Can update any room
     * STAFF: Can update any room
     * HOTEL_OWNER: Can update rooms in own hotel
     */
    public function update(User $user, HotelRoom $room): bool
    {
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }

        if ($user->role === 'hotel_owner') {
            return $user->id === $room->hotel->owner_id;
        }

        return false;
    }

    /**
     * ADMIN: Can delete rooms
     * STAFF: CANNOT delete
     * HOTEL_OWNER: Cannot delete
     */
    public function delete(User $user, HotelRoom $room): bool
    {
        return $user->role === 'admin';
    }
}
