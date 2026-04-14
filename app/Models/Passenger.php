<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Passenger extends Model
{
    use HasFactory;

    protected $fillable = [
        'trek_booking_id',
        'full_name',
        'passport_number',
        'age',
    ];

    /**
     * Yo relation method le model lai trekBooking relation sanga map garcha.
     *
     * Why:
     * Yo relation le trekBooking sanga linked data eager-load ra filter query ma safely reuse garna help garcha.
     */
    public function trekBooking(): BelongsTo
    {
        return $this->belongsTo(TrekBooking::class);
    }
}
