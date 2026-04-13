<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Notifications\TwoFactorDisabledNotification;
use App\Notifications\TwoFactorEnabledNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TwoFactorSettingsController extends Controller
{
    public function enable(Request $request): RedirectResponse
    {
        $request->user()->update([
            'two_factor_enabled' => true,
        ]);

        // ✅ Notificación de activación
        $request->user()->notify(new TwoFactorEnabledNotification());
        return back()->with('success', 'Verificación en dos pasos activada.');
    }

    public function disable(Request $request): RedirectResponse
    {
        $request->user()->update([
            'two_factor_enabled'     => false,
            'two_factor_code'        => null,
            'two_factor_expires_at'  => null,
        ]);
        // ✅ Notificación de desactivación
        $request->user()->notify(new TwoFactorDisabledNotification());
        return back()->with('success', 'Verificación en dos pasos desactivada.');
    }
}
