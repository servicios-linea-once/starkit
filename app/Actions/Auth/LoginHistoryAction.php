<?php

namespace App\Actions\Auth;

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Http\Request;

class LoginHistoryAction
{
    public function record(User $user, Request $request, bool $successful = true): void
    {
        LoginHistory::create([
            'user_id'      => $user->id,
            'session_id'   =>   session()->getId(),
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent() ?? 'Desconocido',
            'location'     => null, // puedes integrar ip-api.com aquí
            'successful'   => $successful,
            'logged_in_at' => now(),
        ]);
    }
}
