<?php

namespace App\Services\Authorization;

use App\Models\User;

/**
 * Authorization helper service for common permission checks
 * 
 * Use this in controllers/services to keep authorization logic DRY
 * Example: AuthorizationHelper::canManageOperations(auth()->user())
 */
class AuthorizationHelper
{
    /**
     * Check if user is admin
     */
    public static function isAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Check if user is staff
     */
    public static function isStaff(User $user): bool
    {
        return $user->role === 'staff';
    }

    /**
     * Check if user is staff or admin
     */
    public static function isOperational(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Check if user is customer
     */
    public static function isCustomer(User $user): bool
    {
        return $user->role === 'customer';
    }

    /**
     * Check if user is hotel owner
     */
    public static function isHotelOwner(User $user): bool
    {
        return $user->role === 'hotel_owner';
    }

    /**
     * Check if user can manage operations (staff/admin)
     */
    public static function canManageOperations(User $user): bool
    {
        return static::isOperational($user);
    }

    /**
     * Check if user can manage users (admin only)
     */
    public static function canManageUsers(User $user): bool
    {
        return static::isAdmin($user);
    }

    /**
     * Check if user can approve users/hotels (admin only)
     */
    public static function canApprove(User $user): bool
    {
        return static::isAdmin($user);
    }

    /**
     * Check if user can access settings (admin only)
     */
    public static function canAccessSettings(User $user): bool
    {
        return static::isAdmin($user);
    }

    /**
     * Check if user can access payment gateway (admin only)
     */
    public static function canAccessPaymentGateway(User $user): bool
    {
        return static::isAdmin($user);
    }

    /**
     * Check if user can view payments (admin/staff)
     */
    public static function canViewPayments(User $user): bool
    {
        return static::isOperational($user);
    }

    /**
     * Check if user can refund payments (admin only)
     */
    public static function canRefundPayments(User $user): bool
    {
        return static::isAdmin($user);
    }

    /**
     * Check if user can create foundational items (admin only)
     */
    public static function canCreateItems(User $user): bool
    {
        return static::isAdmin($user);
    }

    /**
     * Check if user can delete items (admin only)
     */
    public static function canDeleteItems(User $user): bool
    {
        return static::isAdmin($user);
    }

    /**
     * Check if user can moderate reviews/content (admin/staff)
     */
    public static function canModerate(User $user): bool
    {
        return static::isOperational($user);
    }

    /**
     * Check if user can access admin dashboard (admin only)
     */
    public static function canAccessAdminDashboard(User $user): bool
    {
        return static::isAdmin($user);
    }

    /**
     * Check if user can view reports/analytics (admin only)
     */
    public static function canViewReports(User $user): bool
    {
        return static::isAdmin($user);
    }
}
