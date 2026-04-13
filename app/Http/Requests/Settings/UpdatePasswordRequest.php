<?php

namespace App\Http\Requests\Settings;

use App\Actions\Auth\PasswordValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    use PasswordValidationRules;

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password'         => $this->passwordRules(),
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required'         => 'La contraseña actual es obligatoria.',
            'current_password.current_password'  => 'La contraseña actual no es correcta.',
            'password.required'                  => 'La nueva contraseña es obligatoria.',
            'password.confirmed'                 => 'Las contraseñas no coinciden.',
        ];
    }
}
