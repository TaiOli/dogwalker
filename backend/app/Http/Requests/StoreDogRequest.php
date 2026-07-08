<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDogRequest extends FormRequest
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
            'nome' => 'required|string',
            'idade' => 'nullable|integer',
            'porte' => 'required|string',
            'raca' => 'nullable|string',
            'foto' => 'nullable|string',
            'observacoes' => 'nullable|string',
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
            'nome.required' => 'Informe o nome do cachorro.',
            'nome.string' => 'O nome do cachorro deve conter um texto válido.',
            'idade.integer' => 'A idade deve ser informada com um número válido.',
            'raca.string' => 'A raça deve conter um texto válido.',
            'foto.string' => 'A foto deve ser informada corretamente.',
            'observacoes.string' => 'As observações devem conter um texto válido.',
        ];
    }
}