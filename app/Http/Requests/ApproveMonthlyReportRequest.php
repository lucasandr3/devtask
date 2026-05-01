<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApproveMonthlyReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'approver_name' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'approver_name.required' => 'O nome do aprovador é obrigatório.',
            'approver_name.max' => 'O nome do aprovador não pode ter mais de 255 caracteres.',
        ];
    }
}
