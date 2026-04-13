<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\TwoFactorAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\LoginNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class TwoFactorController extends Controller
{
    public function create(Request $request): Response|RedirectResponse
    {
        if (! $request->session()->has('auth.2fa_user_id')) {
            return redirect()->route('login');
        }

        return Inertia::render('Auth/TwoFactor', [
            'status' => session('status'),
        ]);
    }

    public function store(Request $request, TwoFactorAction $action): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ], [
            'code.required' => 'El código de verificación es obligatorio.',
            'code.size'     => 'El código debe tener exactamente 6 dígitos.',
        ]);

        $userId = $request->session()->get('auth.2fa_user_id');
        $user   = User::findOrFail($userId);

        if (! $action->verify($user, $request->code)) {
            return back()->withErrors([
                'code' => 'El código es incorrecto o ha expirado.',
            ]);
        }

        $remember = $request->session()->get('auth.2fa_remember', false);
        $request->session()->forget(['auth.2fa_user_id', 'auth.2fa_remember']);

        Auth::login($user, $remember);
        $request->session()->regenerate();

        $user->notify(new LoginNotification(
            $request->ip(),
            $request->userAgent() ?? 'Desconocido'
        ));

        return redirect()->intended(route('dashboard'));
    }

    public function resend(Request $request, TwoFactorAction $action): RedirectResponse
    {
        if (! $request->session()->has('auth.2fa_user_id')) {
            return redirect()->route('login');
        }

        $user = User::findOrFail($request->session()->get('auth.2fa_user_id'));
        $action->generate($user);

        return back()->with('status', 'Código reenviado a tu correo.');
    }
}
