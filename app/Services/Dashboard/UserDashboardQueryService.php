<?php

namespace App\Services\Dashboard;

use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\TrekBooking;
use App\Models\User;


/**
 * Yo UserDashboardQueryService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class UserDashboardQueryService
{
    /**
     * Yo method le customer dashboard ko bookings ra stats aggregate garera return garcha.
     *
     * Why:
     * Yo query rule service ma rak्दा controller slim rahanchha ra data shape sabai screen ma consistent dekhinchha.
     */
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

    /**
     * Yo method le staff dashboard ko booking stats, chart data, ra recent records prepare garcha.
     *
     * Why:
     * Yo query rule service ma rak्दा controller slim rahanchha ra data shape sabai screen ma consistent dekhinchha.
     */
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

    /**
     * Yo method le hotel owner dashboard ko hotels, bookings, ra revenue stats build garcha.
     *
     * Why:
     * Yo query rule service ma rak्दा controller slim rahanchha ra data shape sabai screen ma consistent dekhinchha.
     */
    public function hotelOwnerData(User $user): array
    {
        $hotels = Hotel::query()
            ->withCount(['rooms', 'gallery'])
            ->where('owner_id', $user->id)
            ->latest()
            ->get();

        $bookingBaseQuery = HotelBooking::query()
            ->whereHas('hotelRoom.hotel', fn ($query) => $query->where('owner_id', $user->id));

        $statusBreakdown = (clone $bookingBaseQuery)
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return [
            'hotels' => $hotels,
            'hotelBookings' => (clone $bookingBaseQuery)
                ->with(['user', 'hotelRoom.hotel'])
                ->latest()
                ->take(8)
                ->get(),
            'stats' => [
                'hotels' => $hotels->count(),
                'rooms' => (int) $hotels->sum('rooms_count'),
                'active_bookings' => (clone $bookingBaseQuery)
                    ->whereIn('status', ['pending', 'confirmed', 'cancellation_requested'])
                    ->count(),
                'bookings_this_month' => (clone $bookingBaseQuery)
                    ->whereYear('check_in', now()->year)
                    ->whereMonth('check_in', now()->month)
                    ->count(),
                'pending_requests' => (int) ($statusBreakdown['pending'] ?? 0),
                'cancellation_requests' => (int) ($statusBreakdown['cancellation_requested'] ?? 0),
                'upcoming_checkins' => (clone $bookingBaseQuery)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->whereBetween('check_in', [today(), today()->addDays(7)])
                    ->count(),
                'revenue_this_month' => (float) (clone $bookingBaseQuery)
                    ->whereIn('status', ['confirmed', 'completed'])
                    ->whereYear('check_in', now()->year)
                    ->whereMonth('check_in', now()->month)
                    ->sum('total_price'),
            ],
            'statusBreakdown' => [
                'pending' => (int) ($statusBreakdown['pending'] ?? 0),
                'confirmed' => (int) ($statusBreakdown['confirmed'] ?? 0),
                'cancellation_requested' => (int) ($statusBreakdown['cancellation_requested'] ?? 0),
                'completed' => (int) ($statusBreakdown['completed'] ?? 0),
                'cancelled' => (int) ($statusBreakdown['cancelled'] ?? 0),
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
                ->whereIn('status', ['confirmed', 'completed'])
                ->whereBetween('check_in', [$start->toDateString(), $end->toDateString()])
                ->sum('total_price');

            $weeks->push(['label' => 'Week ' . (4 - $i), 'revenue' => (float)$revenue]);
        }
        return $weeks->toArray();
    }
}







