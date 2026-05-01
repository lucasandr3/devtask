<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'numero' => ['required', 'string', 'max:255'],
            'serie' => ['nullable', 'string', 'max:10'],
            'data_emissao' => ['required', 'date'],
            'valor' => ['required', 'numeric', 'min:0'],
            'descricao' => ['nullable', 'string', 'max:1000'],
            'arquivo' => ['nullable', 'file', 'mimes:pdf', 'max:10240'], // max 10MB
            'service_code' => ['nullable', 'string', 'max:50'],
            'iss_value' => ['nullable', 'numeric', 'min:0'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
            'invoice_type' => ['nullable', 'string', 'in:service,product'],
        ];
    }

    public function messages(): array
    {
        return [
            'numero.required' => 'O campo número é obrigatório.',
            'numero.string' => 'O campo número deve ser um texto.',
            'data_emissao.required' => 'O campo data de emissão é obrigatório.',
            'data_emissao.date' => 'O campo data de emissão deve ser uma data válida.',
            'valor.required' => 'O campo valor é obrigatório.',
            'valor.numeric' => 'O campo valor deve ser um número.',
            'valor.min' => 'O campo valor deve ser maior ou igual a zero.',
            'arquivo.file' => 'O arquivo deve ser um arquivo válido.',
            'arquivo.mimes' => 'O arquivo deve ser um PDF.',
            'arquivo.max' => 'O arquivo não pode ser maior que 10MB.',
            'iss_value.numeric' => 'O campo valor do ISS deve ser um número.',
            'iss_value.min' => 'O campo valor do ISS deve ser maior ou igual a zero.',
            'tax_amount.numeric' => 'O campo valor de impostos deve ser um número.',
            'tax_amount.min' => 'O campo valor de impostos deve ser maior ou igual a zero.',
            'invoice_type.in' => 'O tipo de nota deve ser serviço ou produto.',
        ];
    }
}
