<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GearRental extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gear_item_id',
        'rental_reference',
        'quantity',
        'status',
        'notes',
        'expected_return_date',
    ];

    protected $casts = [
        'expected_return_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gearItem(): BelongsTo
    {
        return $this->belongsTo(GearItem::class);
    }
}
