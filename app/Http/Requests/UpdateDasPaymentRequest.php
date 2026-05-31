<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\PreparesBrazilianNumericInput;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDasPaymentRequest extends FormRequest
{
    use PreparesBrazilianNumericInput;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->prepareBrazilianMoneyFields(['amount']);
    }

    public function rules(): array
    {
        return [
            'reference_month' => ['required', 'date'],
            'due_date' => ['required', 'date'],
            'payment_date' => ['nullable', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'receipt_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // max 10MB
        ];
    }

    public function messages(): array
    {
        return [
            'reference_month.required' => 'O campo mês de referência é obrigatório.',
            'reference_month.date' => 'O campo mês de referência deve ser uma data válida.',
            'due_date.required' => 'O campo data de vencimento é obrigatório.',
            'due_date.date' => 'O campo data de vencimento deve ser uma data válida.',
            'payment_date.date' => 'O campo data de pagamento deve ser uma data válida.',
            'amount.required' => 'O campo valor é obrigatório.',
            'amount.numeric' => 'O campo valor deve ser um número.',
            'amount.min' => 'O campo valor deve ser maior ou igual a zero.',
            'receipt_file.file' => 'O arquivo deve ser um arquivo válido.',
            'receipt_file.mimes' => 'O arquivo deve ser PDF, JPG, JPEG ou PNG.',
            'receipt_file.max' => 'O arquivo não pode ser maior que 10MB.',
        ];
    }
}
