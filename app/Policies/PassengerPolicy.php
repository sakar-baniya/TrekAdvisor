<?php

namespace App\Policies;

use App\Models\Passenger;
use App\Models\User;

class PassengerPolicy
{
    /**
     * ADMIN: Can view all passengers
     * STAFF: Can view all passengers (operations)
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * ADMIN: Can view any passenger
     * STAFF: Can view any passenger
     */
    public function view(User $user, Passenger $passenger): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * ADMIN: Can create passengers
     * STAFF: Can create passengers (during booking process)
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'customer']);
    }

    /**
     * ADMIN: Can update any passenger
     * STAFF: Can update any passenger
     */
    public function update(User $user, Passenger $passenger): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * ADMIN: Can delete passengers
     * STAFF: CANNOT delete
     */
    public function delete(User $user, Passenger $passenger): bool
    {
        return $user->role === 'admin';
    }
}
