<?php

namespace App\Http\Requests;

use App\DTOs\Evaluation\CreateEvaluationDTO;
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

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'nota.required' => 'Informe uma nota para avaliar.',
            'nota.min' => 'A nota mínima é 1.',
            'nota.max' => 'A nota máxima é 5.',
        ];
    }

      public function toDto(): CreateEvaluationDTO
      {
         /** @var array<string, mixed> $validated */
        $validated = $this->validated();

         return CreateEvaluationDTO::fromRequest($validated);
      }
}