<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:todo,doing,done,cancelled'],
            'work_date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'Status inválido.',
            'work_date.required' => 'A data de trabalho é obrigatória.',
            'work_date.date' => 'A data de trabalho deve ser uma data válida.',
        ];
    }
}
