@props(['group'])

@php
    $project = $group['project'];
    $tasks = $group['tasks'];
    $groupKey = $project ? 'project-' . $project->id : 'no-project';
@endphp

<div
    class="card overflow-hidden task-project-group"
    data-project-group
    x-data="{
        open: localStorage.getItem('tasks-group-{{ $groupKey }}') !== 'closed',
        toggle() {
            this.open = !this.open;
            localStorage.setItem('tasks-group-{{ $groupKey }}', this.open ? 'open' : 'closed');
        }
    }"
>
    <div
        class="flex items-center gap-3 px-4 py-4 sm:px-6 cursor-pointer select-none transition-colors hover:bg-muted/50"
        :class="open ? 'border-b border-border' : ''"
        @click="toggle()"
        role="button"
        :aria-expanded="open"
    >
        <x-ui.icon
            name="chevron-down"
            class="h-4 w-4 shrink-0 text-muted-foreground transition-transform duration-200"
            x-bind:class="open ? '' : '-rotate-90'"
        />

        <div class="flex min-w-0 flex-1 items-center justify-between gap-3">
            @if($project)
                <a
                    href="{{ route('projetos.show', $project) }}"
                    class="min-w-0 truncate font-semibold text-foreground hover:text-primary"
                    @click.stop
                >
                    {{ $project->name }}
                </a>
            @else
                <span class="font-semibold text-muted-foreground">Sem projeto</span>
            @endif

            <span class="shrink-0 text-xs text-muted-foreground">
                {{ $tasks->count() }} {{ $tasks->count() === 1 ? 'tarefa' : 'tarefas' }}
            </span>
        </div>
    </div>

    <div x-show="open" x-cloak>
        <div class="data-table-scroll">
            <table class="data-table">
                <thead class="data-table-header">
                    <tr>
                        <x-data-table.header>Título</x-data-table.header>
                        <x-data-table.header class="data-table-th-compact">Status</x-data-table.header>
                        <x-data-table.header align="right" class="data-table-th-actions">Ações</x-data-table.header>
                    </tr>
                </thead>
                <tbody class="data-table-body">
                    @foreach($tasks as $task)
                        <x-data-table.row class="task-row">
                            <x-data-table.title-cell :title="$task->title" />
                            <x-data-table.cell class="data-table-td-compact">
                                <x-status-badge :status="$task->status->label()" :color="$task->status->color()" />
                            </x-data-table.cell>
                            <x-data-table.actions
                                :viewRoute="route('tarefas.show', $task)"
                                :editRoute="route('tarefas.editar', $task)"
                                :deleteRoute="\App\Support\CurrentCompany::canManageProjects() ? route('tarefas.destroy', $task) : null"
                                deleteConfirm="Tem certeza que deseja excluir esta tarefa?"
                            />
                        </x-data-table.row>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
