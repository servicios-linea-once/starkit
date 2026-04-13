<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar cache de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ─── Permisos ───────────────────────────────────────────
        $permissions = [
            // Usuarios
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Roles
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            // Dashboard
            'dashboard.view',

            // Configuración
            'settings.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ─── Roles y asignación de permisos ─────────────────────

        // Admin — acceso total
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($permissions);

        // Manager — gestión de usuarios, sin roles ni delete
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->syncPermissions([
            'users.view',
            'users.create',
            'users.edit',
            'dashboard.view',
            'settings.view',
        ]);

        // User — solo dashboard y configuración propia
        $user = Role::firstOrCreate(['name' => 'user']);
        $user->syncPermissions([
            'dashboard.view',
            'settings.view',
        ]);
    }
}
