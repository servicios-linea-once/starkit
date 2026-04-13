<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Limpia caché de permisos de Spatie antes de seedear
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->call([
            RolePermissionSeeder::class, // Primero: roles y permisos
            UserSeeder::class,           // Segundo: usuarios (necesitan los roles)

        ]);
    }
}
