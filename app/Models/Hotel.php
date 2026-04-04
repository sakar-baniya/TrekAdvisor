<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Hotel extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function rooms(): HasMany
    {
        return $this->hasMany(HotelRoom::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * All images for this hotel (ordered by sort_order)
     */
    public function images(): HasMany
    {
        return $this->hasMany(HotelImage::class)->orderBy('sort_order');
    }

    /**
     * Featured/primary image (first in sort_order)
     * Replaces old single image field
     */
    public function image()
    {
        return $this->images()->first();
    }

    public function gallery(): HasMany
    {
        return $this->hasMany(HotelImage::class)->orderBy('sort_order')->orderBy('id');
    }
}
