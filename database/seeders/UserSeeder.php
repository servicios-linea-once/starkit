<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin ───────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@starkit.test'],
            [
                'name'              => 'Admin StarKit',
                'password'          => Hash::make('Admin1234!'),
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // ─── Manager ─────────────────────────────────────────────
        $manager = User::firstOrCreate(
            ['email' => 'manager@starkit.test'],
            [
                'name'              => 'Manager StarKit',
                'password'          => Hash::make('Manager1234!'),
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );
        $manager->assignRole('manager');

        // ─── Usuario normal ───────────────────────────────────────
        $user = User::firstOrCreate(
            ['email' => 'user@starkit.test'],
            [
                'name'              => 'Usuario StarKit',
                'password'          => Hash::make('User1234!'),
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );
        $user->assignRole('user');

        // ─── Usuario inactivo (para pruebas) ──────────────────────
        $inactive = User::firstOrCreate(
            ['email' => 'inactive@starkit.test'],
            [
                'name'              => 'Usuario Inactivo',
                'password'          => Hash::make('Inactive1234!'),
                'is_active'         => false,
                'email_verified_at' => now(),
            ]
        );
        $inactive->assignRole('user');

        // ─── Usuario con 2FA activo (para pruebas) ────────────────
        $twoFactor = User::firstOrCreate(
            ['email' => '2fa@starkit.test'],
            [
                'name'               => 'Usuario 2FA',
                'password'           => Hash::make('TwoFactor1234!'),
                'is_active'          => true,
                'two_factor_enabled' => true,
                'email_verified_at'  => now(),
            ]
        );
        $twoFactor->assignRole('user');
    }
}
