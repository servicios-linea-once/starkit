<?php

namespace App\Http\Requests\Auth;

use App\Actions\Auth\PasswordValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    use PasswordValidationRules;

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => $this->passwordRules(),
        ];
    }

    public function messages(): array
    {
        return [
            'token.required'     => 'El token de restablecimiento es obligatorio.',
            'email.required'     => 'El correo electrónico es obligatorio.',
            'password.required'  => 'La nueva contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}
