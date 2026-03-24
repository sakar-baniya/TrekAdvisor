<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class GearItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'daily_price',
        'total_stock',
        'available_stock',
        'image',
    ];

    public function rentals(): HasMany
    {
        return $this->hasMany(GearRental::class);
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
}
