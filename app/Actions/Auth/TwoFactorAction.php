<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Notifications\TwoFactorCodeNotification;

class TwoFactorAction
{
    public function generate(User $user): void
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->forceFill([
            'two_factor_code'       => $code,
            'two_factor_expires_at' => now()->addMinutes(10),
        ])->save();

        $user->notify(new TwoFactorCodeNotification($code));
    }

    public function verify(User $user, string $code): bool
    {
        if (
            $user->two_factor_code === $code &&
            $user->two_factor_expires_at !== null &&
            now()->lessThanOrEqualTo($user->two_factor_expires_at)
        ) {
            $user->forceFill([
                'two_factor_code'       => null,
                'two_factor_expires_at' => null,
            ])->save();

            return true;
        }

        return false;
    }
}
