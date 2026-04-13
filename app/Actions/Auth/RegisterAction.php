<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Notifications\Auth\WelcomeNotification;
use Illuminate\Support\Facades\Hash;

class RegisterAction
{
    public function execute(array $data): User
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => true,
        ]);

        // Asigna rol por defecto
        $user->assignRole('user');

        // Notificación de bienvenida
        $user->notify(new WelcomeNotification($user));

        return $user;
    }
}
