<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSiteLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'segment' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:10000'],
            'website' => ['nullable', 'max:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'website.max' => 'Não foi possível processar o envio.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function leadAttributes(): array
    {
        return [
            'name' => $this->string('name')->toString(),
            'email' => $this->string('email')->toString(),
            'company_name' => $this->filled('company') ? $this->string('company')->toString() : null,
            'phone' => $this->filled('phone') ? $this->string('phone')->toString() : null,
            'segment' => $this->filled('segment') ? $this->string('segment')->toString() : null,
            'message' => $this->string('message')->toString(),
        ];
    }
}
