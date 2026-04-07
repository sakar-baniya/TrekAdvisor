<?php

namespace App\Services\Dashboard;

use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\Payment;
use App\Models\Trek;
use App\Models\TrekBooking;
use App\Models\User;
use Illuminate\Support\Collection;

class AdminDashboardQueryService
{
    public function data(): array
    {
        return [
            'stats' => [
                'bookings_this_month' => (TrekBooking::query()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count()
                    + HotelBooking::query()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count()) ?: 0,
                'revenue_this_month' => Payment::query()
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->where('status', 'success')
                    ->sum('amount') ?: 0,
                'total_users' => User::query()->count() ?: 0,
                'active_treks' => Trek::query()->where('status', 'active')->count() ?: 0,
                'active_hotels' => Hotel::query()->where('status', 'active')->count() ?: 0,
                'pending_hotels' => Hotel::query()->where('status', 'pending')->count() ?: 0,
            ],
            'charts' => [
                'revenue' => $this->getRevenueTrend(),
                'status_distribution' => $this->getStatusDistribution(),
            ],
            'recentBookings' => $this->recentBookings()->take(10),
        ];
    }

    private function getRevenueTrend(): array
    {
        $months = collect([]);
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M');
            
            $revenue = Payment::query()
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->where('status', 'success')
                ->sum('amount');

            $months->push(['month' => $monthName, 'revenue' => (float)$revenue]);
        }
        return $months->toArray();
    }

    private function getStatusDistribution(): array
    {
        $trekStatus = TrekBooking::query()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $hotelStatus = HotelBooking::query()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Merge status counts
        $allStatuses = array_merge_recursive($trekStatus, $hotelStatus);
        $finalStatus = [];
        foreach($allStatuses as $status => $counts) {
            $finalStatus[$status] = is_array($counts) ? array_sum($counts) : $counts;
        }

        return $finalStatus;
    }

    public function recentBookings(): Collection
    {
        $trekBookings = TrekBooking::query()
            ->with(['user', 'departure.trek'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function (TrekBooking $booking) {
                return (object) [
                    'reference' => $booking->booking_reference,
                    'title' => $booking->departure?->trek?->title ?? 'Trek booking',
                    'customer' => $booking->user?->name ?? 'Unknown customer',
                    'details' => $booking->total_passengers . ' passengers',
                    'amount' => $booking->total_price,
                    'status' => $booking->status,
                    'type' => 'Trek',
                    'created_at' => $booking->created_at,
                ];
            });

        $hotelBookings = HotelBooking::query()
            ->with(['user', 'hotelRoom.hotel'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function (HotelBooking $booking) {
                return (object) [
                    'reference' => $booking->booking_reference,
                    'title' => $booking->hotelRoom?->hotel?->name ?? 'Hotel booking',
                    'customer' => $booking->user?->name ?? 'Unknown customer',
                    'details' => $booking->num_rooms . ' rooms, ' . $booking->num_nights . ' nights',
                    'amount' => $booking->total_price,
                    'status' => $booking->status,
                    'type' => 'Hotel',
                    'created_at' => $booking->created_at,
                ];
            });

        return collect()
            ->merge($trekBookings)
            ->merge($hotelBookings)
            ->sortByDesc('created_at')
            ->values();
    }
}


