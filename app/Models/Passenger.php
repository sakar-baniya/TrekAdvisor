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

    public function trekBooking(): BelongsTo
    {
        return $this->belongsTo(TrekBooking::class);
    }
}
