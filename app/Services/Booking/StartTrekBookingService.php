<?php

namespace App\Services\Booking;

use App\Models\Departure;
use RuntimeException;

class StartTrekBookingService
{
    public function __construct(
        private readonly BookingSessionService $bookingSessionService,
    ) {
    }

    public function loadDeparture(Departure $departure): Departure
    {
        $departure->load('trek');

        return $departure;
    }

    public function handle(int $departureId, int $totalPassengers): void
    {
        $departure = Departure::query()->findOrFail($departureId);

        if (($departure->booked_seats + $totalPassengers) > $departure->capacity) {
            throw new RuntimeException('Not enough slots available for this departure.');
        }

        $this->bookingSessionService->store($departure, $totalPassengers);
    }
}
