<?php

namespace App\Http\Requests;

use App\DTOs\User\CreateUserDTO;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLoginRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required|string'
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'email.required' => 'Informe o email.',
            'email.email' => 'Informe um email válido.',
            'password.required' => 'Informe a senha é obrigatório.'
        ];
    }

    public function toDto(): CreateUserDTO
    {
        /** @var array<string, mixed> $validated */
        $validated = $this->validated();

        return CreateUserDTO::fromRequest($validated);
    }
}