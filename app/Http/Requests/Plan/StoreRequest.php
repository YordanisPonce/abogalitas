<?php

namespace App\Http\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'plan_id' => 'nullable',
            'features' => 'required|array',
            'features.*.description' => 'required',
            'features.*.value' => 'nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'description' => 'descripción',
            'price' => 'precio',
            'plan_id' => 'plan',
            'features' => 'características',
            'features.*.description' => 'descripción de características',
            'features.*.value' => 'valor de características',
        ];
    }
}
