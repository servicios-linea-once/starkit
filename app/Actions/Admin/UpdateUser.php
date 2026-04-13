<?php

namespace App\Actions\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUser
{
    public function handle(User $user, array $input): User
    {
        $user->fill([
            'name'      => $input['name'],
            'email'     => $input['email'],
            'is_active' => $input['is_active'] ?? $user->is_active,
        ]);

        if (! empty($input['password'])) {
            $user->password = Hash::make($input['password']);
        }

        $user->save();
        $user->syncRoles([$input['role']]);

        return $user;
    }
}
