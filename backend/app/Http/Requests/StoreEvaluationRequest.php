<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'passeio_id' => 'required|exists:passeios,id',
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string',
            'tipo_avaliador' => 'required|in:tutor,passeador',
        ];
    }
}