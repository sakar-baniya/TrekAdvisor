<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrekImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'trek_id',
        'path',
        'is_placeholder',
        'sort_order',
    ];

    public function trek(): BelongsTo
    {
        return $this->belongsTo(Trek::class);
    }
}
