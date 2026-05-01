<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePullRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'repo' => ['required', 'string', 'max:255'],
            'pr_number' => ['required', 'integer', 'min:1'],
            'title' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url'],
            'status' => ['required', 'string', 'max:255'],
            'work_date' => ['required', 'date'],
            'task_id' => [
                'nullable',
                'exists:tasks,id',
                function ($attribute, $value, $fail) {
                    if ($value && !\App\Models\Task::where('id', $value)->where('user_id', auth()->id())->exists()) {
                        $fail('A tarefa selecionada não pertence a você.');
                    }
                },
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Garante que task_id seja null se vazio
        if ($this->has('task_id') && $this->task_id === '') {
            $this->merge(['task_id' => null]);
        }
    }

    public function messages(): array
    {
        return [
            'repo.required' => 'O repositório é obrigatório.',
            'pr_number.required' => 'O número do PR é obrigatório.',
            'pr_number.integer' => 'O número do PR deve ser um número inteiro.',
            'title.required' => 'O título é obrigatório.',
            'url.required' => 'A URL é obrigatória.',
            'url.url' => 'A URL deve ser válida.',
            'status.required' => 'O status é obrigatório.',
            'work_date.required' => 'A data de trabalho é obrigatória.',
            'work_date.date' => 'A data de trabalho deve ser uma data válida.',
        ];
    }
}
