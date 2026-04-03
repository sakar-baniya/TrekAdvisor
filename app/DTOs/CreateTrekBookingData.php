<?php

namespace App\DTOs;

use App\Http\Requests\ConfirmBookingRequest;

class CreateTrekBookingData
{
    public function __construct(
        public readonly int $userId,
        public readonly int $departureId,
        public readonly int $totalPassengers,
        public readonly float $pricePerPerson,
        public readonly array $passengers,
    ) {
    }

    public static function fromRequest(ConfirmBookingRequest $request, array $bookingData): self
    {
        return new self(
            userId: (int) $request->user()->id,
            departureId: (int) $bookingData['departure_id'],
            totalPassengers: (int) $bookingData['total_passengers'],
            pricePerPerson: (float) $bookingData['price_per_person'],
            passengers: $request->validated('passengers', []),
        );
    }

    public function totalPrice(): float
    {
        return $this->pricePerPerson * $this->totalPassengers;
    }
}
