<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTourRequest extends FormRequest
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
            'dog_id' => 'required|exists:dogs,id',
            'passeador_id' => 'nullable|exists:users,id',
            'data' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'duracao' => 'required|date_format:H:i',
            'local' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
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
            'dog_id.required' => 'Selecione um cachorro.',
            'dog_id.exists' => 'O cachorro informado não foi encontrado.',
            'data.required' => 'Informe a data do passeio.',
            'data.date' => 'Informe uma data válida.',
            'data.after_or_equal' => 'A data do passeio não pode ser anterior ao dia atual.',
            'hora.required' => 'Informe o horário do passeio.',
            'duracao.required' => 'Informe a duração do passeio.',
            'duracao.date_format' => 'A duração deve estar no formato hora:minuto.',
            'local.required' => 'Informe o local do passeio.',
            'local.string' => 'O local deve conter um texto válido.',
            'local.max' => 'O local não pode ter mais de :max caracteres.',
            'valor.required' => 'Informe o valor do passeio.',
            'valor.numeric' => 'O valor deve ser um número válido.',
            'valor.min' => 'O valor não pode ser menor que :min.',
        ];
    }
}