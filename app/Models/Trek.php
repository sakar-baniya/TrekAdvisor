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

    public function getImageAttribute(): ?string
    {
        $path = $this->images->first()?->path
            ?? $this->images()->first()?->path;

        if (!$path) return null;

        // External URLs or already-prefixed paths must be returned as-is
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/storage/')) {
            return $path;
        }

        return \Illuminate\Support\Facades\Storage::url($path);
    }

    /**
     * Legacy gallery relationship (keeping for backward compatibility)
     */
    public function gallery(): HasMany
    {
        return $this->hasMany(TrekImage::class);
    }
}
