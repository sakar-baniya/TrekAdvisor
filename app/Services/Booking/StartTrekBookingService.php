<?php

namespace App\Services\Booking;

use App\Models\Departure;
use App\Repositories\Contracts\DepartureRepositoryInterface;
use RuntimeException;

class StartTrekBookingService
{
    public function __construct(
        private readonly DepartureRepositoryInterface $departures,
        private readonly BookingSessionService $bookingSessionService,
    ) {
    }

    public function loadDeparture(Departure $departure): Departure
    {
        return $this->departures->loadTrek($departure);
    }

    public function handle(int $departureId, int $totalPassengers): void
    {
        $departure = $this->departures->findOrFail($departureId);

        if (! $this->departures->hasCapacity($departure, $totalPassengers)) {
            throw new RuntimeException('Not enough slots available for this departure.');
        }

        $this->bookingSessionService->store($departure, $totalPassengers);
    }
}
