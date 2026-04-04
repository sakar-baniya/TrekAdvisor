<?php

/**
 * Blade authorization helpers - add to config/blade.php or use directly
 * 
 * These make authorization checks cleaner in Blade templates:
 * 
 * @isAdmin - Check if user is admin
 * @isStaff - Check if user is staff
 * @isOperational - Check if user is admin or staff
 * @isCustomer - Check if user is customer
 * @isHotelOwner - Check if user is hotel owner
 * 
 * Usage in Blade:
 *   @isAdmin
 *       <p>Only admins see this</p>
 *   @endisAdmin
 *   
 *   @isOperational
 *       <p>Admins and staff see this</p>
 *   @endisOperational
 */

use Illuminate\Support\Facades\Blade;
use App\Services\Authorization\AuthorizationHelper;

// Check if user is admin
Blade::if('isAdmin', function () {
    return auth()->check() && AuthorizationHelper::isAdmin(auth()->user());
});

// Check if user is staff
Blade::if('isStaff', function () {
    return auth()->check() && AuthorizationHelper::isStaff(auth()->user());
});

// Check if user is operational (admin/staff)
Blade::if('isOperational', function () {
    return auth()->check() && AuthorizationHelper::isOperational(auth()->user());
});

// Check if user is customer
Blade::if('isCustomer', function () {
    return auth()->check() && AuthorizationHelper::isCustomer(auth()->user());
});

// Check if user is hotel owner
Blade::if('isHotelOwner', function () {
    return auth()->check() && AuthorizationHelper::isHotelOwner(auth()->user());
});
