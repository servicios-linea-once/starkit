<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginHistoryAction;
use App\Actions\Auth\TwoFactorAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Notifications\LoginNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status'           => session('status'),
        ]);
    }

    public function store(LoginRequest $request, TwoFactorAction $twoFactorAction, LoginHistoryAction $history): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        if ($user->two_factor_enabled) {
            Auth::logout();

            $request->session()->put('auth.2fa_user_id',  $user->id);
            $request->session()->put('auth.2fa_remember', $request->boolean('remember'));

            $twoFactorAction->generate($user);

            return redirect()->route('two-factor.create');
        }

        $request->session()->regenerate();

        $user->notify(new LoginNotification(
            $request->ip(),
            $request->userAgent() ?? 'Desconocido'
        ));
        $history->record($user, $request);

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
