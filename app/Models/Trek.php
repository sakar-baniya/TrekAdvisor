<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trek extends Model
{
    public function itineraries() {
        return $this->hasMany(Itinerary::class);
    }
    public function departures() {
        return $this->hasMany(Departure::class);
    }
}
