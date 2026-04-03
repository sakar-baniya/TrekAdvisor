<?php

namespace App\Repositories\Eloquent;

use App\Models\Departure;
use App\Repositories\Contracts\DepartureRepositoryInterface;

class EloquentDepartureRepository implements DepartureRepositoryInterface
{
    public function findOrFail(int $id): Departure
    {
        return Departure::query()->findOrFail($id);
    }

    public function loadTrek(Departure $departure): Departure
    {
        $departure->load('trek');

        return $departure;
    }

    public function hasCapacity(Departure $departure, int $totalPassengers): bool
    {
        return ($departure->booked_seats + $totalPassengers) <= $departure->capacity;
    }
}
