<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case STAFF = 'staff';
    case CUSTOMER = 'customer';
    case HOTEL_OWNER = 'hotel_owner';

    /**
     * Get human readable label
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::STAFF => 'Staff',
            self::CUSTOMER => 'Customer',
            self::HOTEL_OWNER => 'Hotel Owner',
        };
    }

    /**
     * Get description
     */
    public function description(): string
    {
        return match ($this) {
            self::ADMIN => 'Full system access',
            self::STAFF => 'Manage treks, departures, bookings',
            self::CUSTOMER => 'Book treks and hotels',
            self::HOTEL_OWNER => 'Manage own hotel',
        };
    }
}
