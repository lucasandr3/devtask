<?php

namespace App\Http\Requests;

use App\Enums\PunchType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePunchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::enum(PunchType::class)],
            'time' => ['nullable', 'regex:/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'O tipo de batida é obrigatório.',
            'type.enum' => 'Tipo de batida inválido.',
            'time.regex' => 'O formato da hora deve ser HH:MM (ex: 14:30).',
        ];
    }
}
