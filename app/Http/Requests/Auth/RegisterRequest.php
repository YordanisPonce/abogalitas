<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', Rule::unique('users')->whereNull('deleted_at')],
            'password' => [
                'required',
                'confirmed',
                Password::min(6)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->numbers()
                    ->symbols()
            ],
            'name' => 'required',
            "address" => "nullable|string",
            "phone_number" => "nullable|string",
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'correo electrónico',
            'password' => 'contraseña',
            'name' => 'nombre',
            'address' => 'dirección',
            'phone_number' => 'número de teléfono',
        ];
    }

}
