<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Editar Tarefa</h2>
    </x-slot>

    <x-ui.page-back :href="route('tarefas.show', $task)" class="mb-6" />

    <div>
        <div class="card p-6">
            <form method="POST" action="{{ route('tarefas.update', $task) }}" class="space-y-6">
                @csrf
                @method('PUT')

                @if($canManage)
                <div>
                    <x-input-label for="project_id" value="Projeto" />
                    <select name="project_id" id="project_id" required class="input mt-1">
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ (string) old('project_id', $task->project_id) === (string) $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('project_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="assigned_to" value="Responsável" />
                    <select name="assigned_to" id="assigned_to" class="input mt-1">
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ (string) old('assigned_to', $task->assigned_to) === (string) $member->id ? 'selected' : '' }}>
                                {{ $member->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('assigned_to')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="title" value="Titulo" />
                    <x-text-input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" required class="mt-1" placeholder="Título da tarefa" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>
                @else
                <div>
                    <x-input-label value="Titulo" />
                    <p class="mt-1 text-foreground font-medium">{{ $task->title }}</p>
                    @if($task->project)
                        <p class="text-sm text-muted-foreground mt-1">Projeto: {{ $task->project->name }}</p>
                    @endif
                </div>
                @endif

                <div>
                    <x-input-label for="description" value="Descricao" />
                    <textarea name="description" id="description" rows="3" class="input mt-1" placeholder="Descreva a tarefa...">{{ old('description', $task->description) }}</textarea>
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

                @if($canManage)
                <div>
                    <x-input-label for="work_date" value="Data de Trabalho" />
                    <x-text-input type="text" name="work_date" id="work_date" value="{{ old('work_date', $task->work_date->format('Y-m-d')) }}" required class="mt-1" data-datepicker placeholder="Selecione a data" />
                    <x-input-error :messages="$errors->get('work_date')" class="mt-2" />
                </div>
                @endif

                <div class="border-t border-border pt-6">
                    <h3 class="text-md font-semibold text-foreground mb-3">Tempo registrado</h3>
                    <p class="text-sm text-muted-foreground mb-4">
                        Total: <strong data-task-total-minutes data-task-id="{{ $task->id }}">{{ minutesToHours($task->totalTrackedMinutes()) }}</strong>
                    </p>
                    <div class="flex gap-2 items-center">
                        @include('tasks.partials.timer-button', ['task' => $task, 'activeTimerTaskId' => $activeTimerTaskId ?? null])
                        <a href="{{ route('tarefas.show', $task) }}" class="btn-secondary">Ver detalhes</a>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-border">
                    <a href="{{ route('tarefas.index') }}" class="btn-secondary">Cancelar</a>
                    <x-primary-button>Salvar</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
