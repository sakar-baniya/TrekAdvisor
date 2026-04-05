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
        'payable_type',
        'payable_id',
        'gateway',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'status',
        'paid_at',
        'gateway_response',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the associated booking or rental for the payment.
     */
    public function payable()
    {
        if ($this->payable_type === 'trek') {
            return $this->belongsTo(TrekBooking::class, 'payable_id');
        } elseif ($this->payable_type === 'hotel') {
            return $this->belongsTo(HotelBooking::class, 'payable_id');
        } elseif ($this->payable_type === 'gear') {
            return $this->belongsTo(GearRental::class, 'payable_id');
        }
        return null;
    }
}
