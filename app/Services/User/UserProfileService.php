<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Validation\ValidationException;

/**
 * UserProfileService
 * 
 * Handles user profile management operations
 * - Update profile information
 * - Change email/phone
 * - Validate changes
 */
class UserProfileService
{
    /**
     * Update user profile
     */
    public function updateProfile(User $user, array $data): User
    {
        $this->validate($user, $data);

        $user->update([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'phone' => $data['phone'] ?? $user->phone,
            'address' => $data['address'] ?? $user->address,
        ]);

        return $user;
    }

    /**
     * Validate profile update data
     */
    private function validate(User $user, array $data): void
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|digits:10',
            'address' => 'nullable|string|max:500',
        ];

        validator($data, $rules)->validate();
    }

    /**
     * Check if email is available
     */
    public function isEmailAvailable(string $email, int $userId = null): bool
    {
        $query = User::where('email', $email);

        if ($userId) {
            $query->where('id', '!=', $userId);
        }

        return $query->doesntExist();
    }

    /**
     * Get user profile data
     */
    public function getProfile(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'role' => $user->role,
            'avatar_initials' => strtoupper(substr($user->name, 0, 2)),
            'joined_at' => $user->created_at->format('M d, Y'),
        ];
    }
}
