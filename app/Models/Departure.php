<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departure extends Model
{
    use HasFactory;

    protected $fillable = [
        'trek_id',
        'start_date',
        'end_date',
        'price',
        'capacity',
        'booked_seats',
        'available_slots',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function trek(): BelongsTo
    {
        return $this->belongsTo(Trek::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(TrekBooking::class);
    }
}
