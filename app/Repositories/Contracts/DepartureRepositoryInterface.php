<?php

namespace App\Repositories\Contracts;

use App\Models\Departure;

interface DepartureRepositoryInterface
{
    public function findOrFail(int $id): Departure;

    public function loadTrek(Departure $departure): Departure;

    public function hasCapacity(Departure $departure, int $totalPassengers): bool;
}
