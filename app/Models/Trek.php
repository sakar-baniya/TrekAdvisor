<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trek extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function itineraries() {
        return $this->hasMany(Itinerary::class);
    }
    public function departures() {
        return $this->hasMany(Departure::class);
    }
    public function reviews() {
        return $this->morphMany(Review::class, 'reviewable');
    }
}
