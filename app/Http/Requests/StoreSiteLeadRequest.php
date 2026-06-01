<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

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
            'company' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'segment' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:10000'],
            'source' => ['required', 'string', 'max:100'],
            'privacyConsent' => ['required', 'accepted'],
            'privacyPolicyVersion' => [
                'required',
                'string',
                'max:32',
                'regex:/^\d{4}-\d{2}-\d{2}$/',
                Rule::in(config('site-legal.accepted_privacy_versions', ['2026-06-02'])),
            ],
            'privacyConsentedAt' => ['required', 'date'],
            'website' => ['nullable', 'max:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'website.max' => 'Não foi possível processar o envio.',
            'privacyConsent.required' => 'Não foi possível processar o envio.',
            'privacyConsent.accepted' => 'Não foi possível processar o envio.',
            'privacyPolicyVersion.required' => 'Não foi possível processar o envio.',
            'privacyPolicyVersion.regex' => 'Não foi possível processar o envio.',
            'privacyPolicyVersion.in' => 'Não foi possível processar o envio.',
            'privacyConsentedAt.required' => 'Não foi possível processar o envio.',
            'privacyConsentedAt.date' => 'Não foi possível processar o envio.',
            'name.required' => 'Não foi possível processar o envio.',
            'company.required' => 'Não foi possível processar o envio.',
            'email.required' => 'Não foi possível processar o envio.',
            'email.email' => 'Não foi possível processar o envio.',
            'phone.required' => 'Não foi possível processar o envio.',
            'segment.required' => 'Não foi possível processar o envio.',
            'message.required' => 'Não foi possível processar o envio.',
            'source.required' => 'Não foi possível processar o envio.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $body = ['message' => 'Não foi possível processar o envio.'];

        if (config('app.debug')) {
            $body['errors'] = $validator->errors();
        }

        throw new HttpResponseException(response()->json($body, 422));
    }

    /**
     * @return array<string, mixed>
     */
    public function leadAttributes(): array
    {
        return [
            'name' => $this->string('name')->toString(),
            'email' => $this->string('email')->toString(),
            'company_name' => $this->string('company')->toString(),
            'phone' => $this->string('phone')->toString(),
            'segment' => $this->string('segment')->toString(),
            'message' => $this->string('message')->toString(),
            'source' => $this->string('source')->toString(),
            'privacy_consent' => true,
            'privacy_policy_version' => $this->string('privacyPolicyVersion')->toString(),
            'privacy_consented_at' => $this->date('privacyConsentedAt'),
        ];
    }
}
