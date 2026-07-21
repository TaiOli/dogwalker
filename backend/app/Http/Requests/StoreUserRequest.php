<?php

namespace App\Http\Requests;

use App\DTOs\User\CreateUserDTO;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'username' => 'required|string|max:25',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'nome' => 'required|string',
            'telefone' => 'nullable|string',
            'tipo_usuario' => 'required|in:tutor,passeador',
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