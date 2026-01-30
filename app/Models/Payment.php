<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'amount',
        'currency',
        'payment_for',
        'reference_id',
        'gateway',
        'status',
        'gateway_response',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the associated booking or rental for the payment.
     */
    public function reference()
    {
        if ($this->payment_for === 'trek') {
            return $this->belongsTo(TrekBooking::class, 'reference_id');
        } elseif ($this->payment_for === 'hotel') {
            return $this->belongsTo(HotelBooking::class, 'reference_id');
        } elseif ($this->payment_for === 'gear') {
            return $this->belongsTo(GearRental::class, 'reference_id');
        }
        return null;
    }
}
