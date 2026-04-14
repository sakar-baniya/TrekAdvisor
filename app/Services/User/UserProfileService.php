<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Validation\ValidationException;


/**
 * Yo UserProfileService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class UserProfileService
{
    /**
     * Yo method le updateProfile related state change safely apply garcha.
     *
     * Why:
     * Write workflow ko validation ra status change ekai thau ma rakhda data mismatch ra side-effect bug kam hunchha.
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
     * Yo method le isEmailAvailable ko service-level kaam handle garcha.
     *
     * Why:
     * Yo method ko business rule service layer ma rakhda future change garna ra test garna sajilo hunchha.
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
     * Yo method le getProfile related data prepare/fetch garcha.
     *
     * Why:
     * Yo query rule service ma rak्दा controller slim rahanchha ra data shape sabai screen ma consistent dekhinchha.
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



