<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'nullable|string|max:25',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users')->ignore($this->route('id')),
            ],
            'password' => 'nullable|min:6',
            'nome' => 'nullable|string',
            'telefone' => 'nullable|string',
            'tipo_usuario' => 'nullable|in:tutor,passeador',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
