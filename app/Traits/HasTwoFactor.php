<?php

namespace App\Traits;

trait HasTwoFactor
{
    /**
     * 2FA activado y confirmado por el usuario
     */
    public function hasTwoFactorEnabled(): bool
    {
        return ! is_null($this->two_factor_secret)
            && ! is_null($this->two_factor_confirmed_at);
    }

    /**
     * Secret generado pero aún no confirmado (escaneó QR pero no validó código)
     */
    public function hasTwoFactorPending(): bool
    {
        return ! is_null($this->two_factor_secret)
            && is_null($this->two_factor_confirmed_at);
    }

    /**
     * Desactiva el 2FA limpiando todos los campos relacionados
     */
    public function disableTwoFactor(): void
    {
        $this->forceFill([
            'two_factor_secret'         => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at'   => null,
        ])->save();
    }

    /**
     * Retorna los recovery codes como array
     */
    public function getRecoveryCodes(): array
    {
        if (is_null($this->two_factor_recovery_codes)) {
            return [];
        }

        return json_decode(decrypt($this->two_factor_recovery_codes), true);
    }

    /**
     * Verifica si un recovery code es válido y lo elimina tras usarlo
     */
    public function useRecoveryCode(string $code): bool
    {
        $codes = $this->getRecoveryCodes();
        $index = array_search($code, $codes);

        if ($index === false) {
            return false;
        }

        // Elimina el código usado (single use)
        unset($codes[$index]);

        $this->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode(array_values($codes))),
        ])->save();

        return true;
    }
}
