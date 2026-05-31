<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title truncate">{{ $task->title }}</h2>
    </x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <x-ui.page-back :href="route('tarefas.index')" />

        @if($canManage)
            <a href="{{ route('tarefas.editar', $task) }}" class="btn-secondary btn-responsive">Editar</a>
        @endif
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-6">
            <div class="card p-6">
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <h3 class="text-base font-semibold text-foreground">Descrição</h3>
                    <x-status-badge :status="$task->status->label()" :color="$task->status->color()" />
                </div>
                @if($task->project)
                    <p class="text-sm text-muted-foreground mb-4">Projeto: {{ $task->project->name }}</p>
                @endif
                @if($task->description)
                    <p class="text-sm text-muted-foreground whitespace-pre-wrap">{{ $task->description }}</p>
                @else
                    <p class="text-sm text-muted-foreground italic">Sem descrição.</p>
                @endif
            </div>

            <form method="POST" action="{{ route('tarefas.update', $task) }}" class="card p-6 space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect_to_show" value="1">

                <div>
                    <x-input-label for="status" value="Status" />
                    <select name="status" id="status" required class="input mt-1">
                        <option value="todo" {{ old('status', $task->status->value) === 'todo' ? 'selected' : '' }}>A Fazer</option>
                        <option value="doing" {{ old('status', $task->status->value) === 'doing' ? 'selected' : '' }}>Em Andamento</option>
                        <option value="done" {{ old('status', $task->status->value) === 'done' ? 'selected' : '' }}>Concluída</option>
                        <option value="cancelled" {{ old('status', $task->status->value) === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>

                @if($canManage || $isAssignee)
                    <div>
                        <x-input-label for="description" value="Atualizar descrição" />
                        <textarea name="description" id="description" rows="3" class="input mt-1" placeholder="Descreva a tarefa...">{{ old('description', $task->description) }}</textarea>
                    </div>
                @endif

                <div>
                    <x-input-label for="executor_notes" value="Notas do executor" />
                    <p class="text-xs text-muted-foreground mb-2">Visível para o responsável e gestores. Registre progresso, bloqueios e observações.</p>
                    <textarea
                        name="executor_notes"
                        id="executor_notes"
                        rows="4"
                        class="input mt-1"
                        placeholder="Ex.: Aguardando revisão de código, testes pendentes..."
                        @disabled(! $canManage && ! $isAssignee)
                    >{{ old('executor_notes', $task->executor_notes) }}</textarea>
                </div>

                @if($canManage)
                    <div>
                        <x-input-label for="internal_notes" value="Notas internas (gestão)" />
                        <p class="text-xs text-muted-foreground mb-2">Visível apenas para líderes e gestores. Não aparece para o executor.</p>
                        <textarea name="internal_notes" id="internal_notes" rows="4" class="input mt-1" placeholder="Observações internas da equipe...">{{ old('internal_notes', $task->internal_notes) }}</textarea>
                    </div>
                @endif

                @if($canManage || $isAssignee)
                    <div class="flex justify-end pt-2 border-t border-border">
                        <x-primary-button>Salvar alterações</x-primary-button>
                    </div>
                @endif
            </form>

            <div class="card p-6">
                <h3 class="text-base font-semibold text-foreground mb-4">Histórico de tempo</h3>

                @if($task->timeEntries->whereNotNull('ended_at')->isEmpty())
                    <p class="text-sm text-muted-foreground italic">Nenhum registro de tempo ainda.</p>
                @else
                    <div class="space-y-3">
                        @foreach($task->timeEntries->whereNotNull('ended_at') as $entry)
                            <div class="flex items-center justify-between gap-4 py-2 border-b border-border last:border-0 text-sm">
                                <div>
                                    <p class="font-medium text-foreground">{{ $entry->user?->name ?? 'Usuário' }}</p>
                                    <p class="text-xs text-muted-foreground">
                                        {{ $entry->started_at->format('d/m/Y H:i') }}
                                        @if($entry->ended_at)
                                            — {{ $entry->ended_at->format('H:i') }}
                                        @endif
                                    </p>
                                </div>
                                <span class="font-medium text-foreground whitespace-nowrap">
                                    {{ minutesToHours($entry->duration_minutes ?? 0) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="card p-6">
                <h3 class="text-base font-semibold text-foreground mb-4">Cronômetro</h3>

                @php
                    $isTimerActive = (int) ($activeTimerTaskId ?? 0) === (int) $task->id;
                @endphp

                <div class="text-center py-4 rounded-lg bg-muted/50 mb-4">
                    <p class="text-xs uppercase tracking-wide text-muted-foreground mb-1">Tempo total</p>
                    <p class="text-2xl font-bold text-foreground" data-task-total-minutes data-task-id="{{ $task->id }}">
                        {{ minutesToHours($task->totalTrackedMinutes()) }}
                    </p>
                    <p
                        data-task-timer-display
                        data-task-id="{{ $task->id }}"
                        data-mode="elapsed"
                        @class(['text-sm font-medium text-primary mt-2', 'hidden' => ! $isTimerActive])
                    >
                        @if($isTimerActive && $runningEntry)
                            {{ gmdate('H:i:s', (int) $runningEntry->started_at->diffInSeconds(now())) }}
                        @endif
                    </p>
                </div>

                @if($isAssignee || $canManage)
                    <div class="flex justify-center">
                        @include('tasks.partials.timer-button', ['task' => $task, 'activeTimerTaskId' => $activeTimerTaskId])
                    </div>
                    <p class="text-xs text-muted-foreground text-center mt-3">
                        @if($isTimerActive)
                            Cronômetro ativo nesta tarefa.
                        @elseif($activeTimerTaskId && ! $isTimerActive)
                            Há um cronômetro ativo em outra tarefa. Ao iniciar aqui, o anterior será parado.
                        @else
                            Clique para iniciar o registro de tempo.
                        @endif
                    </p>
                @else
                    <p class="text-sm text-muted-foreground text-center">Somente o responsável pode usar o cronômetro.</p>
                @endif
            </div>

            <div class="card p-6 space-y-4">
                <h3 class="text-base font-semibold text-foreground">Informações</h3>

                <div>
                    <p class="text-xs text-muted-foreground">Responsável</p>
                    <p class="text-sm font-medium text-foreground">{{ $task->assignee?->name ?? 'Não definido' }}</p>
                </div>

                <div>
                    <p class="text-xs text-muted-foreground">Criado por</p>
                    <p class="text-sm font-medium text-foreground">{{ $task->creator?->name ?? '—' }}</p>
                </div>

                <div>
                    <p class="text-xs text-muted-foreground">Data de trabalho</p>
                    <p class="text-sm font-medium text-foreground">{{ $task->work_date->format('d/m/Y') }}</p>
                </div>

                <div>
                    <p class="text-xs text-muted-foreground">Criada em</p>
                    <p class="text-sm font-medium text-foreground">{{ $task->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div>
                    <p class="text-xs text-muted-foreground">Última atualização</p>
                    <p class="text-sm font-medium text-foreground">{{ $task->updated_at->format('d/m/Y H:i') }}</p>
                </div>

                @if($task->pullRequests->isNotEmpty())
                    <div>
                        <p class="text-xs text-muted-foreground">Pull requests vinculados</p>
                        <p class="text-sm font-medium text-foreground">{{ $task->pullRequests->count() }}</p>
                    </div>
                @endif
            </div>

            @if($canManage && $task->internal_notes)
                <div class="card p-6 border-amber-500/30 bg-amber-500/5">
                    <h3 class="text-base font-semibold text-foreground mb-2">Notas internas</h3>
                    <p class="text-sm text-muted-foreground whitespace-pre-wrap">{{ $task->internal_notes }}</p>
                </div>
            @endif

            @if($task->executor_notes)
                <div class="card p-6">
                    <h3 class="text-base font-semibold text-foreground mb-2">Notas do executor</h3>
                    <p class="text-sm text-muted-foreground whitespace-pre-wrap">{{ $task->executor_notes }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
