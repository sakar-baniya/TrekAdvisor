<?php

namespace App\Services\Booking;

use App\Models\Departure;


/**
 * Yo BookingSessionService service le yo file ko business logic organize garcha.
 *
 * Why:
 * Reusable service steps banauda controller ko code clean ra maintainable rahanchha.
 */
class BookingSessionService
{
    public const SESSION_KEY = 'trek_booking_session';

    /**
     * Yo method le store related business flow execute garcha.
     *
     * Why:
     * Write workflow ko validation ra status change ekai thau ma rakhda data mismatch ra side-effect bug kam hunchha.
     */
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

    /**
     * Yo method le get related data prepare/fetch garcha.
     *
     * Why:
     * Yo query rule service ma rak्दा controller slim rahanchha ra data shape sabai screen ma consistent dekhinchha.
     */
    public function get(): ?array
    {
        $bookingData = session(self::SESSION_KEY);

        return is_array($bookingData) ? $bookingData : null;
    }

    /**
     * Yo method le clear ko service-level kaam handle garcha.
     *
     * Why:
     * Yo method ko business rule service layer ma rakhda future change garna ra test garna sajilo hunchha.
     */
    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }
}






