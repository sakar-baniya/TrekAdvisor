<?php

namespace App\Policies;

use App\Models\GearItem;
use App\Models\User;

class GearItemPolicy
{
    /**
     * ADMIN: Can view all gear items
     * STAFF: Can view all gear items
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * ADMIN: Can view any gear item
     * STAFF: Can view any gear item
     */
    public function view(User $user, GearItem $gearItem): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * ADMIN: Can create gear items
     * STAFF: CANNOT create (admin creates all)
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN: Can update any gear item
     * STAFF: Can update any gear item
     */
    public function update(User $user, GearItem $gearItem): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * ADMIN: Can delete gear items
     * STAFF: CANNOT delete
     */
    public function delete(User $user, GearItem $gearItem): bool
    {
        return $user->role === 'admin';
    }
}
