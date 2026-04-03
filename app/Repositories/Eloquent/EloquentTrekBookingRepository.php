<?php

namespace App\Repositories\Eloquent;

use App\Models\Passenger;
use App\Models\TrekBooking;
use App\Repositories\Contracts\TrekBookingRepositoryInterface;

class EloquentTrekBookingRepository implements TrekBookingRepositoryInterface
{
    public function create(array $attributes): TrekBooking
    {
        return TrekBooking::query()->create($attributes);
    }

    public function createPassengers(TrekBooking $booking, array $passengers): void
    {
        foreach ($passengers as $passenger) {
            Passenger::query()->create([
                'trek_booking_id' => $booking->id,
                'name' => $passenger['name'],
                'passport_no' => $passenger['passport_no'],
                'age' => $passenger['age'],
            ]);
        }
    }

    public function loadForCheckout(TrekBooking $booking): TrekBooking
    {
        $booking->load('departure.trek', 'user');

        return $booking;
    }

    public function findForCheckoutById(int $id): ?TrekBooking
    {
        return TrekBooking::query()
            ->with('departure.trek', 'user')
            ->find($id);
    }

    public function findForDisplayById(int $id): ?TrekBooking
    {
        return TrekBooking::query()
            ->with('departure')
            ->find($id);
    }

    public function markConfirmedAndIncrementSeats(TrekBooking $booking): TrekBooking
    {
        if ($booking->status !== 'Confirmed') {
            $booking->status = 'Confirmed';
            $booking->save();

            if ($booking->departure) {
                $booking->departure->increment('booked_seats', $booking->total_passengers);
            }
        }

        return $booking;
    }
}
