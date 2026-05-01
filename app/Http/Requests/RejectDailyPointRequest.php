<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectDailyPointRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'notes.max' => 'As observações não podem ter mais de 1000 caracteres.',
        ];
    }
}
