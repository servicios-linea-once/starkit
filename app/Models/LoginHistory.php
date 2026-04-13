<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginHistory extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'location',
        'successful',
        'logged_in_at',
        'logged_out_at',
    ];

    protected $casts = [
        'successful'   => 'boolean',
        'logged_in_at'  => 'datetime',
        'logged_out_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Parsea el user agent para mostrar navegador/OS legible
    public function getBrowserAttribute(): string
    {
        $ua = $this->user_agent;

        if (str_contains($ua, 'Chrome') && !str_contains($ua, 'Edg'))  return 'Chrome';
        if (str_contains($ua, 'Firefox'))  return 'Firefox';
        if (str_contains($ua, 'Safari') && !str_contains($ua, 'Chrome')) return 'Safari';
        if (str_contains($ua, 'Edg'))      return 'Edge';
        if (str_contains($ua, 'Opera'))    return 'Opera';

        return 'Desconocido';
    }

    public function getOsAttribute(): string
    {
        $ua = $this->user_agent;

        if (str_contains($ua, 'Windows'))  return 'Windows';
        if (str_contains($ua, 'Mac'))      return 'macOS';
        if (str_contains($ua, 'Linux'))    return 'Linux';
        if (str_contains($ua, 'Android'))  return 'Android';
        if (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')) return 'iOS';

        return 'Desconocido';
    }
}
