<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\PreparesBrazilianNumericInput;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkContractRequest extends FormRequest
{
    use PreparesBrazilianNumericInput;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->prepareBrazilianMoneyFields(['contract_value']);
        $this->prepareBrazilianDecimalFields(['monthly_hours']);
    }

    public function rules(): array
    {
        return [
            'company_name' => ['nullable', 'string', 'max:255'],
            'contract_value' => ['nullable', 'numeric', 'min:0'],
            'monthly_hours' => ['required', 'numeric', 'min:0.01'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'monthly_hours.required' => 'O campo horas mensais é obrigatório.',
            'monthly_hours.numeric' => 'O campo horas mensais deve ser um número.',
            'monthly_hours.min' => 'O campo horas mensais deve ser maior que zero.',
            'start_date.required' => 'O campo data de início é obrigatório.',
            'start_date.date' => 'O campo data de início deve ser uma data válida.',
            'end_date.date' => 'O campo data de fim deve ser uma data válida.',
            'end_date.after' => 'A data de fim deve ser posterior à data de início.',
        ];
    }
}
