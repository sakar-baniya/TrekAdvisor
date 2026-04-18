<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'admin_replied_at' => 'datetime',
    ];

    /**
     * Yo relation method le model lai user relation sanga map garcha.
     *
     * Why:
     * Yo relation le user sanga linked data eager-load ra filter query ma safely reuse garna help garcha.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Yo relation method le model lai reviewable relation sanga map garcha.
     *
     * Why:
     * Yo relation le reviewable sanga linked data eager-load ra filter query ma safely reuse garna help garcha.
     */
    public function reviewable()
    {
        return $this->morphTo();
    }
}
