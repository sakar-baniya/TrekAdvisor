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
                'bookings_this_month' => TrekBooking::query()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count()
                    + HotelBooking::query()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
                'revenue_this_month' => Payment::query()
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->where('status', 'success')
                    ->sum('amount'),
                'total_users' => User::query()->count(),
                'active_treks' => Trek::query()->where('status', 'active')->count(),
                'active_hotels' => Hotel::query()->where('status', 'active')->count(),
                'pending_hotels' => Hotel::query()->where('status', 'pending')->count(),
            ],
            'recentBookings' => $this->recentBookings()->take(10),
        ];
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


