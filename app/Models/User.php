<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Traits\HasTwoFactor;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, HasTwoFactor;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
        'avatar_public_id',
        'two_factor_enabled',
        'two_factor_code',
        'two_factor_expires_at',
        'is_active',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at'     => 'datetime',
            'password'              => 'hashed',
            'is_active'             => 'boolean',
            'two_factor_enabled'    => 'boolean',
            'two_factor_expires_at' => 'datetime',
        ];
    }


    public function loginHistories(): HasMany
    {
        return $this->hasMany(LoginHistory::class)->latest('logged_in_at');
    }
    // Dentro del modelo:
    public function sendPasswordResetNotification(#[\SensitiveParameter] $token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
    /** Scope: solo usuarios activos */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
