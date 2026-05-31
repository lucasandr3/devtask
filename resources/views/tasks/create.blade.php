<x-app-layout>

    <x-slot name="header">
        <h2 class="page-title">Nova Tarefa</h2>
    </x-slot>

    <x-ui.page-back :href="route('tarefas.index')" class="mb-6" />

    <div>
        <div class="card p-6">
            <form method="POST" action="{{ route('tarefas.store') }}" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="project_id" value="Projeto" />
                    <select name="project_id" id="project_id" required class="input mt-1">
                        <option value="">Selecione um projeto</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ (string) old('project_id', request('project_id')) === (string) $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('project_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="assigned_to" value="Responsável" />
                    <select name="assigned_to" id="assigned_to" class="input mt-1">
                        <option value="">Eu ({{ auth()->user()->name }})</option>
                        @foreach($members as $member)
                            @if($member->id !== auth()->id())
                                <option value="{{ $member->id }}" {{ (string) old('assigned_to') === (string) $member->id ? 'selected' : '' }}>
                                    {{ $member->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('assigned_to')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="title" value="Titulo" />
                    <x-text-input type="text" name="title" id="title" value="{{ old('title') }}" required class="mt-1" placeholder="Título da tarefa" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" value="Descricao" />
                    <textarea name="description" id="description" rows="3" class="input mt-1" placeholder="Descreva a tarefa...">{{ old('description') }}</textarea>
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

                <div class="flex justify-end gap-3 pt-4 border-t border-border">
                    <a href="{{ route('tarefas.index') }}" class="btn-secondary">Cancelar</a>
                    <x-primary-button>Salvar</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
