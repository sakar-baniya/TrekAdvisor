<?php

namespace App\Repositories\Contracts;

use App\Models\TrekBooking;

interface TrekBookingRepositoryInterface
{
    public function create(array $attributes): TrekBooking;

    public function createPassengers(TrekBooking $booking, array $passengers): void;

    public function loadForCheckout(TrekBooking $booking): TrekBooking;

    public function findForCheckoutById(int $id): ?TrekBooking;

    public function findForDisplayById(int $id): ?TrekBooking;

    public function markConfirmedAndIncrementSeats(TrekBooking $booking): TrekBooking;
}
