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
    protected $appends = ['image'];

    /**
     * Yo relation method le model lai itineraries relation sanga map garcha.
     *
     * Why:
     * Yo relation le itinerarie sanga linked data eager-load ra filter query ma safely reuse garna help garcha.
     */
    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class)->orderBy('day_number');
    }

    /**
     * Yo relation method le model lai departures relation sanga map garcha.
     *
     * Why:
     * Yo relation le departure sanga linked data eager-load ra filter query ma safely reuse garna help garcha.
     */
    public function departures(): HasMany
    {
        return $this->hasMany(Departure::class);
    }

    /**
     * Yo relation method le model lai reviews relation sanga map garcha.
     *
     * Why:
     * Yo relation le review sanga linked data eager-load ra filter query ma safely reuse garna help garcha.
     */
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Yo relation method le model lai images relation sanga map garcha.
     *
     * Why:
     * Yo relation le image sanga linked data eager-load ra filter query ma safely reuse garna help garcha.
     */
    public function images(): HasMany
    {
        return $this->hasMany(TrekImage::class)->orderBy('sort_order');
    }

    /**
     * Yo method le getImageAttribute accessor/mutator behavior define garcha.
     *
     * Why:
     * Read/write data format model level ma control garna yo accessor/mutator method chahinchha.
     */
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
     * Yo relation method le model lai gallery relation sanga map garcha.
     *
     * Why:
     * Yo relation le gallery sanga linked data eager-load ra filter query ma safely reuse garna help garcha.
     */
    public function gallery(): HasMany
    {
        return $this->hasMany(TrekImage::class);
    }
}
