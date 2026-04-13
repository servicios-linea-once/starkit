<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $user = $request->user();

        return array_merge(parent::share($request), [

            'auth' => [
                'user' => \auth()->check() ? [
                    'id'         => $user->id,
                    'name'       => $user->name,
                    'email'      => $user->email,
                    'email_verified_at'      => $user->email_verified_at,
                    'avatar_url' => $user->avatar_url,
                    'roles'      => $user->getRoleNames(),
                    'two_factor_enabled' => $user->two_factor_enabled,
                    'is_active'  => $user->is_active,
                    'created_at'  => $user->created_at,
                ] : null,

                // Permisos disponibles en Vue (can['users.view'], etc.)
                'can' => $user ? [
                    'users.view'    => $user->can('users.view'),
                    'users.create'  => $user->can('users.create'),
                    'users.edit'    => $user->can('users.edit'),
                    'users.delete'  => $user->can('users.delete'),
                    'roles.view'    => $user->can('roles.view'),
                    'roles.create'  => $user->can('roles.create'),
                    'roles.edit'    => $user->can('roles.edit'),
                    'roles.delete'  => $user->can('roles.delete'),
                ] : [],
            ],

            // Flash messages (success / error)
            'flash' => [
                'success' => $request->session()->get('success'),
                'error'   => $request->session()->get('error'),
                'warning' => $request->session()->get('warning'), // ✅
                'info'    => $request->session()->get('info'),    // ✅
            ],
        ]);
    }
}
