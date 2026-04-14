<?php

namespace App\Services\Booking;

use App\Models\Departure;
use RuntimeException;


/**
 * Yo StartTrekBookingService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class StartTrekBookingService
{
    /**
     * Yo method le loadDeparture related data prepare/fetch garcha.
     *
     * Why:
     * Yo query rule service ma rak्दा controller slim rahanchha ra data shape sabai screen ma consistent dekhinchha.
     */
    public function loadDeparture(Departure $departure): Departure
    {
        $departure->load('trek');

        return $departure;
    }

    /**
     * Yo method le handle related business flow execute garcha.
     *
     * Why:
     * Yo method ko business rule service layer ma rakhda future change garna ra test garna sajilo hunchha.
     */
    public function handle(int $departureId, int $totalPassengers): void
    {
        $departure = Departure::query()->findOrFail($departureId);

        if (($departure->booked_seats + $totalPassengers) > $departure->capacity) {
            throw new RuntimeException('Not enough slots available for this departure.');
        }

        $this->bookingSessionService->store($departure, $totalPassengers);
    }
}






