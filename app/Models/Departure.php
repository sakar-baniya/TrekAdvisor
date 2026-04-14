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
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Yo relation method le model lai trek relation sanga map garcha.
     *
     * Why:
     * Yo relation le trek sanga linked data eager-load ra filter query ma safely reuse garna help garcha.
     */
    public function trek(): BelongsTo
    {
        return $this->belongsTo(Trek::class);
    }

    /**
     * Yo relation method le model lai bookings relation sanga map garcha.
     *
     * Why:
     * Yo relation le booking sanga linked data eager-load ra filter query ma safely reuse garna help garcha.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(TrekBooking::class);
    }
}
