<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Dashboard', [
            'kpis' => [
                'total_users'       => User::count(),
                'active_users'      => User::active()->count(),
                'total_roles'       => Role::count(),
                'total_permissions' => Permission::count(),
            ],
            'recent_users' => User::with('roles')
                ->latest()
                ->take(5)
                ->get(['id', 'name', 'email', 'avatar_url', 'is_active', 'created_at']),
        ]);
    }
}
