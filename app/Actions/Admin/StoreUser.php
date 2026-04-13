<?php

namespace App\Actions\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StoreUser
{
    public function handle(array $input): User
    {
        $user = User::create([
            'name'      => $input['name'],
            'email'     => $input['email'],
            'password'  => Hash::make($input['password']),
            'is_active' => $input['is_active'] ?? true,
        ]);

        $user->assignRole($input['role']);

        return $user;
    }
}
