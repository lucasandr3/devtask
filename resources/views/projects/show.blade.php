<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">{{ $project->name }}</h2>
    </x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <x-ui.page-back :href="route('projetos.index')" />

        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
            <x-status-badge :status="$project->status->label()" color="blue" />
            @if(\App\Support\CurrentCompany::canManageProjects())
                <a href="{{ route('projetos.edit', $project) }}" class="btn-secondary btn-responsive">Editar</a>
            @endif
            <a href="{{ route('tarefas.create', ['project_id' => $project->id]) }}" class="btn-primary btn-responsive">Nova Tarefa</a>
        </div>
    </div>

    @if($project->description)
        <p class="text-sm text-muted-foreground mb-6">{{ $project->description }}</p>
    @endif

    <div class="kanban-board grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="kanban-column">
            <div class="kanban-column-header bg-muted rounded-t-lg px-4 py-3 border-b-2 border-gray-400">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-foreground">A Fazer</h3>
                    <span class="text-xs font-medium px-2 py-1 rounded-full bg-gray-200 bg-secondary">{{ $tasksByStatus['todo']->count() }}</span>
                </div>
            </div>
            <div class="kanban-column-body bg-muted/50 dark:bg-neutral-900 rounded-b-lg p-2 min-h-[400px]" data-status="todo" ondrop="dropTask(event)" ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                @forelse($tasksByStatus['todo'] as $task)
                    @include('tasks.partials.kanban-card', ['task' => $task, 'activeTimerTaskId' => $activeTimerTaskId ?? null])
                @empty
                    <div class="kanban-empty text-center py-8 text-gray-400 text-sm">Nenhuma tarefa</div>
                @endforelse
            </div>
        </div>

        <div class="kanban-column">
            <div class="kanban-column-header bg-yellow-50 dark:bg-yellow-900/20 rounded-t-lg px-4 py-3 border-b-2 border-yellow-400">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-yellow-700 dark:text-yellow-400">Em Andamento</h3>
                    <span class="text-xs font-medium px-2 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900/40">{{ $tasksByStatus['doing']->count() }}</span>
                </div>
            </div>
            <div class="kanban-column-body bg-yellow-50/50 dark:bg-yellow-900/10 rounded-b-lg p-2 min-h-[400px]" data-status="doing" ondrop="dropTask(event)" ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                @forelse($tasksByStatus['doing'] as $task)
                    @include('tasks.partials.kanban-card', ['task' => $task, 'activeTimerTaskId' => $activeTimerTaskId ?? null])
                @empty
                    <div class="kanban-empty text-center py-8 text-gray-400 text-sm">Nenhuma tarefa</div>
                @endforelse
            </div>
        </div>

        <div class="kanban-column">
            <div class="kanban-column-header bg-green-50 dark:bg-green-900/20 rounded-t-lg px-4 py-3 border-b-2 border-green-400">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-green-700 dark:text-green-400">Concluída</h3>
                    <span class="text-xs font-medium px-2 py-1 rounded-full bg-green-100 dark:bg-green-900/40">{{ $tasksByStatus['done']->count() }}</span>
                </div>
            </div>
            <div class="kanban-column-body bg-green-50/50 dark:bg-green-900/10 rounded-b-lg p-2 min-h-[400px]" data-status="done" ondrop="dropTask(event)" ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                @forelse($tasksByStatus['done'] as $task)
                    @include('tasks.partials.kanban-card', ['task' => $task, 'activeTimerTaskId' => $activeTimerTaskId ?? null])
                @empty
                    <div class="kanban-empty text-center py-8 text-gray-400 text-sm">Nenhuma tarefa</div>
                @endforelse
            </div>
        </div>

        <div class="kanban-column">
            <div class="kanban-column-header bg-red-50 dark:bg-red-900/20 rounded-t-lg px-4 py-3 border-b-2 border-red-400">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-red-700 dark:text-red-400">Cancelada</h3>
                    <span class="text-xs font-medium px-2 py-1 rounded-full bg-red-100 dark:bg-red-900/40">{{ $tasksByStatus['cancelled']->count() }}</span>
                </div>
            </div>
            <div class="kanban-column-body bg-red-50/50 dark:bg-red-900/10 rounded-b-lg p-2 min-h-[400px]" data-status="cancelled" ondrop="dropTask(event)" ondragover="allowDrop(event)" ondragleave="dragLeave(event)">
                @forelse($tasksByStatus['cancelled'] as $task)
                    @include('tasks.partials.kanban-card', ['task' => $task, 'activeTimerTaskId' => $activeTimerTaskId ?? null])
                @empty
                    <div class="kanban-empty text-center py-8 text-gray-400 text-sm">Nenhuma tarefa</div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
    @include('tasks.partials.kanban-scripts')
    @endpush
</x-app-layout>
