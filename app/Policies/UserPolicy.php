<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * ADMIN: Sabai users haru herna milchha (Can view all users)
     * STAFF: User list herna mildaina (CANNOT view user list)
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN: Jo sukai user ko profile herna pauchha (Can view any user)
     * STAFF: Aafno profile matra herna pauchha (Can view own profile only)
     */
    public function view(User $user, User $model): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->id === $model->id;
    }

    /**
     * ADMIN: Naya user banauna milchha (Can create users)
     * STAFF: Naya user banauna mildaina (CANNOT create users)
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN: Sabai user update garna pauchha (Can update all users)
     * STAFF: Aafno detail matra update garna milchha (Can update own profile only)
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
     * ADMIN: User lai delete garna pauchha (Can delete users)
     * STAFF: Delete garna mildaina (CANNOT delete users)
     */
    public function delete(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN: User ra Hotel Owner lai approve garna milchha (Can approve/reject users)
     * STAFF: Approve garna mildaina (CANNOT approve/reject)
     */
    public function approve(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN: Role change garna pauchha (Can assign roles)
     * STAFF: Role assign garna mildaina (CANNOT assign roles)
     */
    public function assignRole(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }
}
