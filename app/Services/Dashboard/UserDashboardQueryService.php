<?php

namespace App\Services\Dashboard;

use App\Models\GearRental;
use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\TrekBooking;
use App\Models\User;

class UserDashboardQueryService
{
    public function customerData(User $user): array
    {
        return [
            'trekBookings' => TrekBooking::query()
                ->with(['departure.trek'])
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get(),
            'hotelBookings' => HotelBooking::query()
                ->with(['hotelRoom.hotel'])
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get(),
            'gearRentals' => GearRental::query()
                ->with('gearItem')
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get(),
            'stats' => [
                'trek_bookings' => TrekBooking::query()->where('user_id', $user->id)->count(),
                'hotel_bookings' => HotelBooking::query()->where('user_id', $user->id)->count(),
                'gear_rentals' => GearRental::query()->where('user_id', $user->id)->count(),
            ],
        ];
    }

    public function staffData(): array
    {
        return [
            'stats' => [
                'today_trek_bookings' => TrekBooking::query()->whereDate('created_at', today())->count(),
                'today_hotel_bookings' => HotelBooking::query()->whereDate('created_at', today())->count(),
                'active_gear_rentals' => GearRental::query()->where('status', 'Active')->count(),
            ],
        ];
    }

    public function hotelOwnerData(User $user): array
    {
        $hotels = Hotel::query()
            ->withCount(['rooms', 'gallery'])
            ->where('owner_id', $user->id)
            ->latest()
            ->get();

        return [
            'hotels' => $hotels,
            'hotelBookings' => HotelBooking::query()
                ->with(['hotelRoom.hotel'])
                ->whereHas('hotelRoom.hotel', fn ($query) => $query->where('owner_id', $user->id))
                ->latest()
                ->take(8)
                ->get(),
            'stats' => [
                'hotels' => $hotels->count(),
                'rooms' => (int) $hotels->sum('rooms_count'),
                'bookings' => HotelBooking::query()
                    ->whereHas('hotelRoom.hotel', fn ($query) => $query->where('owner_id', $user->id))
                    ->count(),
            ],
        ];
    }
}
