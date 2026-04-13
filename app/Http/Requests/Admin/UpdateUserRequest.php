<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('users.edit');
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($this->route('user')->id),
            ],
            'password'  => ['nullable', 'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers()],
            'role'      => ['required', 'string', 'exists:roles,name'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'El nombre es obligatorio.',
            'email.required'     => 'El correo electrónico es obligatorio.',
            'email.unique'       => 'Este correo ya está en uso por otro usuario.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'role.required'      => 'Debes asignar un rol.',
            'role.exists'        => 'El rol seleccionado no existe.',
        ];
    }
}
