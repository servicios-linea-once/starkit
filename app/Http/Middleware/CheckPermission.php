<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Uso en rutas: middleware('permission:users.view')
     * Soporta múltiples permisos: middleware('permission:users.view|users.edit')
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (! $request->user()?->can($permission)) {
            if ($request->header('X-Inertia')) {
                return response()->json(['message' => 'Forbidden.'], 403);
            }

            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        return $next($request);
    }
}
