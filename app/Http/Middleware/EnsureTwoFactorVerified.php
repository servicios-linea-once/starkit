<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorVerified
{
    /**
     * Bloquea el acceso si el usuario tiene 2FA activo
     * pero aún no ha validado el código en esta sesión.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user &&
            $user->hasTwoFactorEnabled() &&
            ! session()->has('2fa_verified')
        ) {
            // Petición Inertia: devuelve conflicto para que Inertia redirija
            if ($request->header('X-Inertia')) {
                return response()->json(['message' => 'Two factor required.'], 423)
                    ->header('X-Inertia-Location', route('two-factor.challenge'));
            }

            return redirect()->route('two-factor.challenge');
        }

        return $next($request);
    }
}
