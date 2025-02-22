<?php

namespace App\Http\Requests\Auth;

use App\Enums\StudenEnum;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        $userId = auth()->id();
        return [
            'email' => "unique:users,email,$userId,id",
            'name' => 'required|string',
        
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'correo electrónico',
            'name' => 'nombre',
        ];
    }
}
