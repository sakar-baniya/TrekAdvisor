<?php

namespace App\Services\Dashboard;

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
            'stats' => [
                'trek_bookings' => TrekBooking::query()->where('user_id', $user->id)->count(),
                'hotel_bookings' => HotelBooking::query()->where('user_id', $user->id)->count(),
                'upcoming_trips' => TrekBooking::query()
                    ->where('user_id', $user->id)
                    ->whereIn('status', ['pending', 'confirmed', 'cancellation_requested'])
                    ->whereHas('departure', fn ($query) => $query->whereDate('start_date', '>=', today()))
                    ->count()
                    + HotelBooking::query()
                        ->where('user_id', $user->id)
                        ->whereIn('status', ['pending', 'confirmed', 'cancellation_requested'])
                        ->whereDate('check_in', '>=', today())
                        ->count(),
            ],
        ];
    }

    public function staffData(): array
    {
        return [
            'stats' => [
                'today_trek_bookings' => TrekBooking::query()->whereDate('created_at', today())->count(),
                'pending_trek_bookings' => TrekBooking::query()->where('status', 'pending')->count(),
                'cancellation_requests' => TrekBooking::query()->where('status', 'cancellation_requested')->count(),
            ],
            'charts' => [
                'activity' => $this->getStaffActivityTrend(),
            ]
            ,
            'recentTrekBookings' => TrekBooking::query()
                ->with(['user', 'departure.trek'])
                ->latest()
                ->take(8)
                ->get(),
        ];
    }

    private function getStaffActivityTrend(): array
    {
        $days = collect([]);
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayName = $date->format('D');
            $count = TrekBooking::query()->whereDate('created_at', $date)->count()
                    + HotelBooking::query()->whereDate('created_at', $date)->count();
            $days->push(['label' => $dayName, 'count' => $count]);
        }
        return $days->toArray();
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
                'bookings_this_month' => HotelBooking::query()
                    ->whereHas('hotelRoom.hotel', fn ($query) => $query->where('owner_id', $user->id))
                    ->whereMonth('created_at', now()->month)
                    ->count(),
                'revenue_this_month' => (float) HotelBooking::query()
                   ->whereHas('hotelRoom.hotel', fn ($query) => $query->where('owner_id', $user->id))
                   ->whereMonth('created_at', now()->month)
                   ->sum('total_price'),
            ],
            'charts' => [
                'revenue' => $this->getHotelOwnerRevenueTrend($user),
            ]
        ];
    }

    private function getHotelOwnerRevenueTrend(User $user): array
    {
        $weeks = collect([]);
        for ($i = 3; $i >= 0; $i--) {
            $start = now()->subWeeks($i)->startOfWeek();
            $end = now()->subWeeks($i)->endOfWeek();
            
            $revenue = HotelBooking::query()
                ->whereHas('hotelRoom.hotel', fn ($query) => $query->where('owner_id', $user->id))
                ->whereBetween('created_at', [$start, $end])
                ->sum('total_price');

            $weeks->push(['label' => 'Week ' . (4 - $i), 'revenue' => (float)$revenue]);
        }
        return $weeks->toArray();
    }
}

