<?php

namespace App\Http\Requests;

use App\Enums\TipoUsuario;
use App\DTOs\User\CreateUserDTO;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('telefone')) {
            $this->merge([
                'telefone' => preg_replace('/\D/', '', $this->telefone),
            ]);
        }
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|max:25',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
            ],
            'password' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'nome' => 'required|string',
            'telefone' => [
                'nullable',
                'string',
                'regex:/^(1[1-9]|2[12478]|3[1-8]|4[1-9]|5[1345]|6[1-9]|7[134579]|8[1-9]|9[1-9])(9\d{8}|[2-5]\d{7})$/',
            ],
            'tipo_usuario' => ['required', Rule::enum(TipoUsuario::class)],
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
            'username.required' => 'Informe o username é obrigatório.',
            'username.string' => 'O username deve conter um texto válido.',
            'username.max' => 'O nome de usuário não pode ter mais de :max caracteres.',
            'email.required' => 'Informe o email é obrigatório.',
            'email.email' => 'Informe um email válido.',
            'email.unique' => 'Este email já está cadastrado.',
            'password.required' => 'Informe a senha é obrigatória.',
            'password.min' => 'A senha deve ter no mínimo :min caracteres.',
            'nome.required' => 'Informe o nome completo é obrigatório.',
            'nome.string' => 'O nome completo deve conter um texto válido.',
            'telefone.string' => 'O telefone deve conter um texto válido.',
            'telefone.regex' => 'Informe um telefone com DDD válido.',
            'foto.image' => 'O arquivo enviado deve ser uma imagem.',
            'foto.mimes' => 'A foto deve estar nos formatos JPG, JPEG ou PNG.',
            'foto.max' => 'A foto não pode ultrapassar 2MB.',
        ];
    }

    public function toDto(): CreateUserDTO
    {
        /** @var array<string, mixed> $validated */
        $validated = $this->validated();

        return CreateUserDTO::fromRequest($validated);
    }
}