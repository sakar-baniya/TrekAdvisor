<?php

namespace App\Policies;

use App\Models\Departure;
use App\Models\Trek;
use App\Models\User;

class DeparturePolicy
{
    /**
     * ADMIN: Can view all departures
     * STAFF: Can view all departures
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * ADMIN: Can view any departure
     * STAFF: Can view any departure
     */
    public function view(User $user, Departure $departure): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * ADMIN: Can create departures
     * STAFF: CANNOT create (admin creates all)
     */
    public function create(User $user, Trek $trek): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN: Can update all departures
     * STAFF: Can update all departures
     */
    public function update(User $user, Departure $departure): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * ADMIN: Can delete departures
     * STAFF: CANNOT delete
     */
    public function delete(User $user, Departure $departure): bool
    {
        return $user->role === 'admin';
    }
}
