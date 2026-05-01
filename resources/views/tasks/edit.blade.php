<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('tarefas.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="page-title">Editar Tarefa</h2>
        </div>
    </x-slot>

    <div>
        <div class="card p-6">
            <form method="POST" action="{{ route('tarefas.update', $task) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="title" value="Titulo" />
                    <x-text-input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" required class="mt-1" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" value="Descricao" />
                    <textarea name="description" id="description" rows="3" class="input mt-1">{{ old('description', $task->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="status" value="Status" />
                    <select name="status" id="status" required class="input mt-1">
                        <option value="todo" {{ old('status', $task->status->value) === 'todo' ? 'selected' : '' }}>A Fazer</option>
                        <option value="doing" {{ old('status', $task->status->value) === 'doing' ? 'selected' : '' }}>Em Andamento</option>
                        <option value="done" {{ old('status', $task->status->value) === 'done' ? 'selected' : '' }}>Concluída</option>
                        <option value="cancelled" {{ old('status', $task->status->value) === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="work_date" value="Data de Trabalho" />
                    <x-text-input type="text" name="work_date" id="work_date" value="{{ old('work_date', $task->work_date->format('Y-m-d')) }}" required class="mt-1" data-datepicker placeholder="Selecione a data" />
                    <x-input-error :messages="$errors->get('work_date')" class="mt-2" />
                </div>

                @if($task->pullRequests->count() > 0)
                    <div class="border-t border-gray-200 dark:border-slate-700 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pull Requests Vinculados</h3>
                        <div class="space-y-3">
                            @foreach($task->pullRequests as $pr)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-800 rounded-lg">
                                    <div class="flex-1">
                                        <a href="{{ $pr->url }}" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline font-medium">
                                            {{ $pr->repo }} #{{ $pr->pr_number }}: {{ $pr->title }}
                                        </a>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $pr->work_date->format('d/m/Y') }} • {{ $pr->status }}
                                        </p>
                                    </div>
                                    <a href="{{ route('pull-requests.edit', $pr) }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-slate-700">
                    <a href="{{ route('tarefas.index') }}" class="btn-secondary">Cancelar</a>
                    <x-primary-button>Salvar</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
