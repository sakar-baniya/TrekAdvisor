<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * ADMIN: Can view all users
     * STAFF: CANNOT view user list
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN: Can view any user
     * STAFF: Can view own profile only
     */
    public function view(User $user, User $model): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->id === $model->id;
    }

    /**
     * ADMIN: Can create users
     * STAFF: CANNOT create users
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN: Can update all users
     * STAFF: Can update own profile only (not role/approval status)
     */
    public function update(User $user, User $model): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        // Staff can only update their own basic info
        return $user->id === $model->id;
    }

    /**
     * ADMIN: Can delete users
     * STAFF: CANNOT delete users
     */
    public function delete(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN: Can approve/reject users and hotel owners
     * STAFF: CANNOT approve/reject
     */
    public function approve(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN: Can assign roles
     * STAFF: CANNOT assign roles
     */
    public function assignRole(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }
}
