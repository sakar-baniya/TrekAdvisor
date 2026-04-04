<?php

namespace App\Policies;

use App\Models\Itinerary;
use App\Models\User;

class ItineraryPolicy
{
    /**
     * ADMIN: Can view all itineraries
     * STAFF: Can view all itineraries (operations)
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * ADMIN: Can view any itinerary
     * STAFF: Can view any itinerary
     */
    public function view(User $user, Itinerary $itinerary): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * ADMIN: Can create itineraries
     * STAFF: CANNOT create (admin creates all)
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN: Can update any itinerary
     * STAFF: Can update any itinerary (manage details)
     */
    public function update(User $user, Itinerary $itinerary): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * ADMIN: Can delete itineraries
     * STAFF: CANNOT delete
     */
    public function delete(User $user, Itinerary $itinerary): bool
    {
        return $user->role === 'admin';
    }
}
