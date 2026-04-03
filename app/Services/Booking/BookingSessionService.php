<?php

namespace App\Services\Booking;

use App\Models\Departure;

class BookingSessionService
{
    private const SESSION_KEY = 'booking_data';

    public function store(Departure $departure, int $totalPassengers): array
    {
        $bookingData = [
            'departure_id' => $departure->id,
            'total_passengers' => $totalPassengers,
            'price_per_person' => $departure->price,
        ];

        session([self::SESSION_KEY => $bookingData]);

        return $bookingData;
    }

    public function get(): ?array
    {
        $bookingData = session(self::SESSION_KEY);

        return is_array($bookingData) ? $bookingData : null;
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }
}
