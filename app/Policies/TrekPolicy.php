<?php

namespace App\Policies;

use App\Models\Trek;
use App\Models\User;

class TrekPolicy
{
    /**
     * ADMIN: Can view all treks
     * STAFF: Can view all treks
     * CUSTOMER: Can view active treks in shop
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * View individual trek
     */
    public function view(User $user, Trek $trek): bool
    {
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }

        // Customers can view active treks
        return $trek->status === 'Active';
    }

    /**
     * ADMIN: Can create treks
     * STAFF: CANNOT create treks (admin creates all)
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN: Can edit all treks
     * STAFF: Can edit all treks (but not create)
     */
    public function update(User $user, Trek $trek): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * ADMIN: Can delete all treks
     * STAFF: CANNOT delete treks
     */
    public function delete(User $user, Trek $trek): bool
    {
        return $user->role === 'admin';
    }
}
