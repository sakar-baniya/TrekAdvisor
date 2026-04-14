<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Itinerary extends Model
{
    use HasFactory;

    protected $fillable = [
        'trek_id',
        'day_number',
        'title',
        'description',
        'accommodation',
        'meals',
    ];

    /**
     * Yo relation method le model lai trek relation sanga map garcha.
     *
     * Why:
     * Yo relation le trek sanga linked data eager-load ra filter query ma safely reuse garna help garcha.
     */
    public function trek(): BelongsTo
    {
        return $this->belongsTo(Trek::class);
    }
}
