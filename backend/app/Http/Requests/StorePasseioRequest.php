<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePasseioRequest extends FormRequest
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
            'passeio_id' => 'nullable|exists:users,id',
            'data' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'duracao' => 'required|integer|min:10|max:240',
            'local' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
        ];  
    }
}