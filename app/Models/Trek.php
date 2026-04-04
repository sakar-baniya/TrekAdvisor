<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Trek extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class)->orderBy('day_number');
    }

    public function departures(): HasMany
    {
        return $this->hasMany(Departure::class);
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * All images for this trek (ordered by sort_order)
     */
    public function images(): HasMany
    {
        return $this->hasMany(TrekImage::class)->orderBy('sort_order');
    }

    /**
     * Featured/primary image (first in sort_order)
     * Replaces old single image field
     */
    public function image()
    {
        return $this->images()->first();
    }

    /**
     * Legacy gallery relationship (keeping for backward compatibility)
     */
    public function gallery(): HasMany
    {
        return $this->hasMany(TrekImage::class);
    }
}
