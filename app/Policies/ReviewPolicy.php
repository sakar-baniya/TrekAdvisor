<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * ADMIN: Can view all reviews
     * STAFF: Can view all reviews (moderation)
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Everyone can view published reviews
     */
    public function view(User $user, Review $review): bool
    {
        return true; // Reviews are public unless hidden
    }

    /**
     * CUSTOMER: Can create reviews for their bookings
     * ADMIN/STAFF: Cannot create reviews
     */
    public function create(User $user): bool
    {
        return $user->role === 'customer';
    }

    /**
     * CUSTOMER: Can update own review
     * ADMIN: Can update any review
     * STAFF: Cannot update reviews (only moderate)
     */
    public function update(User $user, Review $review): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->id === $review->user_id;
    }

    /**
     * ADMIN: Can delete any review
     * STAFF: CANNOT delete (can only moderate)
     * CUSTOMER: Can delete own review
     */
    public function delete(User $user, Review $review): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->id === $review->user_id;
    }

    /**
     * ADMIN: Can moderate reviews (flag, hide)
     * STAFF: Can moderate reviews (flag, hide)
     */
    public function moderate(User $user, Review $review): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }
}
