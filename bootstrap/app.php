<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\EnsureTwoFactorVerified;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Añade HandleInertiaRequests al grupo web
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);

        // Aliases para usar en rutas con ->middleware('nombre')
        $middleware->alias([
            'auth'        => Authenticate::class,
            'guest'       => RedirectIfAuthenticated::class,
            '2fa'         => EnsureTwoFactorVerified::class,
            'permission'  => CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
