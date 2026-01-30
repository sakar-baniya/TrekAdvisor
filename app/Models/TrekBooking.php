<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrekBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'departure_id',
        'booking_reference',
        'total_passengers',
        'price_per_person',
        'subtotal',
        'discount_percent',
        'discount_amount',
        'total_price',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function departure(): BelongsTo
    {
        return $this->belongsTo(Departure::class);
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(Passenger::class);
    }
}
