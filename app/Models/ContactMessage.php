<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Yo ContactMessage model le contact inbox ko data structure ra relation define garcha.
 *
 * Why:
 * Staff support inbox ma read state, response note, ra responder tracking ko data yehi model bata manage huncha.
 */
class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'subject',
        'message',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

}
