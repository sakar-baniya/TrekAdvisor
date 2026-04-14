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

    /**
     * Yo relation method le model lai user relation sanga map garcha.
     *
     * Why:
     * Yo relation le user sanga linked data eager-load ra filter query ma safely reuse garna help garcha.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Yo relation method le model lai departure relation sanga map garcha.
     *
     * Why:
     * Yo relation le departure sanga linked data eager-load ra filter query ma safely reuse garna help garcha.
     */
    public function departure(): BelongsTo
    {
        return $this->belongsTo(Departure::class);
    }

    /**
     * Yo relation method le model lai passengers relation sanga map garcha.
     *
     * Why:
     * Yo relation le passenger sanga linked data eager-load ra filter query ma safely reuse garna help garcha.
     */
    public function passengers(): HasMany
    {
        return $this->hasMany(Passenger::class);
    }

    /**
     * Yo relation method le model lai payments relation sanga map garcha.
     *
     * Why:
     * Yo relation le payment sanga linked data eager-load ra filter query ma safely reuse garna help garcha.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'payable_id')
            ->where('payable_type', 'trek');
    }
}
