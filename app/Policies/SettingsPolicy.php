<?php

namespace App\Policies;

use App\Models\User;

class SettingsPolicy
{
    /**
     * ADMIN ONLY: Can view system settings
     * STAFF: CANNOT view settings
     */
    public function view(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN ONLY: Can update system settings
     * STAFF: CANNOT update settings
     */
    public function update(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN ONLY: Can access payment gateway settings
     * STAFF: CANNOT access payment gateway
     */
    public function accessPaymentGateway(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN ONLY: Can access website/app settings
     * STAFF: CANNOT access website settings
     */
    public function accessWebsiteSettings(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN ONLY: Can access email/notification settings
     */
    public function accessEmailSettings(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN ONLY: Can manage system integrations
     */
    public function manageIntegrations(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN ONLY: Can access reports/analytics
     */
    public function accessReports(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * ADMIN ONLY: Can access dashboard
     */
    public function accessDashboard(User $user): bool
    {
        return $user->role === 'admin';
    }
}
