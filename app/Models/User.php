<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Yo relation method le model lai casts relation sanga map garcha.
     *
     * Why:
     * Yo relation le cast sanga linked data eager-load ra filter query ma safely reuse garna help garcha.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'approval_status',
        'phone',
        'address',
        'avatar_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Yo relation method le model lai casts relation sanga map garcha.
     *
     * Why:
     * Yo relation le cast sanga linked data eager-load ra filter query ma safely reuse garna help garcha.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'approval_status' => 'string', // enum: pending, approved, rejected
        ];
    }

    /**
     * Yo relation method le model lai dashboardRouteName relation sanga map garcha.
     *
     * Why:
     * Yo relation le dashboardRouteName sanga linked data eager-load ra filter query ma safely reuse garna help garcha.
     */
    public function dashboardRouteName(): string
    {
        return match ($this->role) {
            'admin' => 'admin.dashboard',
            'staff' => 'staff.dashboard',
            'hotel_owner' => 'hotel_owner.dashboard',
            default => 'customer.dashboard',
        };
    }

    /**
     * Yo relation method le model lai isApproved relation sanga map garcha.
     *
     * Why:
     * Yo relation le isApproved sanga linked data eager-load ra filter query ma safely reuse garna help garcha.
     */
    public function isApproved(): bool
    {
        return ($this->approval_status ?? 'pending') === 'approved';
    }
}

