<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDailyPointRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'work_date' => ['required', 'date'],
            'entry_time' => ['nullable', 'regex:/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/'],
            'lunch_out_time' => ['nullable', 'regex:/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/'],
            'lunch_return_time' => ['nullable', 'regex:/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/'],
            'exit_time' => ['nullable', 'regex:/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/'],
            'extra_start_time' => ['nullable', 'regex:/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/'],
            'extra_end_time' => ['nullable', 'regex:/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'work_date.required' => 'A data é obrigatória.',
            'work_date.date' => 'A data deve ser uma data válida.',
            'entry_time.regex' => 'O formato da hora de entrada deve ser HH:MM (ex: 08:00).',
            'lunch_out_time.regex' => 'O formato da hora de saída do almoço deve ser HH:MM (ex: 12:00).',
            'lunch_return_time.regex' => 'O formato da hora de volta do almoço deve ser HH:MM (ex: 13:00).',
            'exit_time.regex' => 'O formato da hora de saída deve ser HH:MM (ex: 18:00).',
            'extra_start_time.regex' => 'O formato da hora de início extra deve ser HH:MM (ex: 18:30).',
            'extra_end_time.regex' => 'O formato da hora de fim extra deve ser HH:MM (ex: 20:00).',
        ];
    }
}
