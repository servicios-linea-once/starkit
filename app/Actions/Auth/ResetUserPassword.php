<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetUserPassword
{
    use PasswordValidationRules;

    public function reset(User $user, array $input): void
    {
        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
