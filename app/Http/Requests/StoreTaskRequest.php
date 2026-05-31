<?php

namespace App\Http\Requests;

use App\Support\CurrentCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = CurrentCompany::id();

        return [
            'project_id' => [
                'required',
                Rule::exists('projects', 'id')->where(fn ($query) => $query->where('company_id', $companyId)),
            ],
            'assigned_to' => [
                'nullable',
                Rule::exists('users', 'id'),
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:todo,doing,done,cancelled'],
            'work_date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required' => 'O projeto é obrigatório.',
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'Status inválido.',
            'work_date.required' => 'A data de trabalho é obrigatória.',
            'work_date.date' => 'A data de trabalho deve ser uma data válida.',
        ];
    }
}
