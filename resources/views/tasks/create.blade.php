<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('tarefas.index') }}" class="p-2 bg-gray-100 dark:bg-slate-700 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="page-title">Nova Tarefa</h2>
        </div>
    </x-slot>

    <div>
        <div class="card p-6">
            <form method="POST" action="{{ route('tarefas.store') }}" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="title" value="Titulo" />
                    <x-text-input type="text" name="title" id="title" value="{{ old('title') }}" required class="mt-1" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" value="Descricao" />
                    <textarea name="description" id="description" rows="3" class="input mt-1">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="status" value="Status" />
                    <select name="status" id="status" required class="input mt-1">
                        <option value="todo" {{ old('status') === 'todo' ? 'selected' : '' }}>A Fazer</option>
                        <option value="doing" {{ old('status') === 'doing' ? 'selected' : '' }}>Em Andamento</option>
                        <option value="done" {{ old('status') === 'done' ? 'selected' : '' }}>Concluída</option>
                        <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="work_date" value="Data de Trabalho" />
                    <x-text-input type="text" name="work_date" id="work_date" value="{{ old('work_date', date('Y-m-d')) }}" required class="mt-1" data-datepicker placeholder="Selecione a data" />
                    <x-input-error :messages="$errors->get('work_date')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-slate-700">
                    <a href="{{ route('tarefas.index') }}" class="btn-secondary">Cancelar</a>
                    <x-primary-button>Salvar</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
