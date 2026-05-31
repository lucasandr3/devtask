<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Tarefas</h2>
    </x-slot>

    {{-- Script para persistir preferência de visualização --}}
    <script>
        (function() {
            const currentView = '{{ $view }}';
            const savedView = localStorage.getItem('tasks_view_preference');
            const urlParams = new URLSearchParams(window.location.search);
            const hasViewParam = urlParams.has('view');
            
            // Se não tem parâmetro na URL e tem preferência salva diferente da atual, redireciona
            if (!hasViewParam && savedView && savedView !== currentView) {
                window.location.href = '{{ route('tarefas.index') }}?view=' + savedView;
            }
        })();
        
        function saveViewPreference(view) {
            localStorage.setItem('tasks_view_preference', view);
        }
    </script>

    {{-- View Toggle + Create Button --}}
    @if($view === 'kanban')
        <x-ui.page-toolbar class="mb-4">
            <x-slot:leading>@include('tasks.partials.toolbar')</x-slot:leading>
            <x-slot:trailing>@include('tasks.partials.toolbar-actions')</x-slot:trailing>
        </x-ui.page-toolbar>
    @endif

    @if($view === 'kanban')
        {{-- Kanban View --}}
        <div class="kanban-board grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            {{-- Coluna A Fazer --}}
            <div class="kanban-column">
                <div class="kanban-column-header bg-muted rounded-t-lg px-4 py-3 border-b-2 border-gray-400">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-gray-400"></span>
                            <h3 class="font-semibold text-foreground">A Fazer</h3>
                        </div>
                        <span class="text-xs font-medium text-muted-foreground bg-gray-200 bg-secondary px-2 py-1 rounded-full">
                            {{ $tasksByStatus['todo']->count() }}
                        </span>
                    </div>
                </div>
                <div class="kanban-column-body bg-muted/50 dark:bg-neutral-900 rounded-b-lg p-2 min-h-[400px]" 
                     data-status="todo" 
                     ondrop="dropTask(event)" 
                     ondragover="allowDrop(event)"
                     ondragleave="dragLeave(event)">
                    @forelse($tasksByStatus['todo'] as $task)
                        @include('tasks.partials.kanban-card', ['task' => $task, 'activeTimerTaskId' => $activeTimerTaskId ?? null])
                    @empty
                        <div class="kanban-empty text-center py-8 text-gray-400 dark:text-muted-foreground text-sm">
                            Nenhuma tarefa
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Coluna Em Andamento --}}
            <div class="kanban-column">
                <div class="kanban-column-header bg-yellow-50 dark:bg-yellow-900/20 rounded-t-lg px-4 py-3 border-b-2 border-yellow-400">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                            <h3 class="font-semibold text-yellow-700 dark:text-yellow-400">Em Andamento</h3>
                        </div>
                        <span class="text-xs font-medium text-yellow-600 dark:text-yellow-400 bg-yellow-100 dark:bg-yellow-900/40 px-2 py-1 rounded-full">
                            {{ $tasksByStatus['doing']->count() }}
                        </span>
                    </div>
                </div>
                <div class="kanban-column-body bg-yellow-50/50 dark:bg-yellow-900/10 rounded-b-lg p-2 min-h-[400px]" 
                     data-status="doing" 
                     ondrop="dropTask(event)" 
                     ondragover="allowDrop(event)"
                     ondragleave="dragLeave(event)">
                    @forelse($tasksByStatus['doing'] as $task)
                        @include('tasks.partials.kanban-card', ['task' => $task, 'activeTimerTaskId' => $activeTimerTaskId ?? null])
                    @empty
                        <div class="kanban-empty text-center py-8 text-gray-400 dark:text-muted-foreground text-sm">
                            Nenhuma tarefa
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Coluna Concluída --}}
            <div class="kanban-column">
                <div class="kanban-column-header bg-green-50 dark:bg-green-900/20 rounded-t-lg px-4 py-3 border-b-2 border-green-400">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-green-400"></span>
                            <h3 class="font-semibold text-green-700 dark:text-green-400">Concluída</h3>
                        </div>
                        <span class="text-xs font-medium text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/40 px-2 py-1 rounded-full">
                            {{ $tasksByStatus['done']->count() }}
                        </span>
                    </div>
                </div>
                <div class="kanban-column-body bg-green-50/50 dark:bg-green-900/10 rounded-b-lg p-2 min-h-[400px]" 
                     data-status="done" 
                     ondrop="dropTask(event)" 
                     ondragover="allowDrop(event)"
                     ondragleave="dragLeave(event)">
                    @forelse($tasksByStatus['done'] as $task)
                        @include('tasks.partials.kanban-card', ['task' => $task, 'activeTimerTaskId' => $activeTimerTaskId ?? null])
                    @empty
                        <div class="kanban-empty text-center py-8 text-gray-400 dark:text-muted-foreground text-sm">
                            Nenhuma tarefa
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Coluna Cancelada --}}
            <div class="kanban-column">
                <div class="kanban-column-header bg-red-50 dark:bg-red-900/20 rounded-t-lg px-4 py-3 border-b-2 border-red-400">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-red-400"></span>
                            <h3 class="font-semibold text-red-700 dark:text-red-400">Cancelada</h3>
                        </div>
                        <span class="text-xs font-medium text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/40 px-2 py-1 rounded-full">
                            {{ $tasksByStatus['cancelled']->count() }}
                        </span>
                    </div>
                </div>
                <div class="kanban-column-body bg-red-50/50 dark:bg-red-900/10 rounded-b-lg p-2 min-h-[400px]" 
                     data-status="cancelled" 
                     ondrop="dropTask(event)" 
                     ondragover="allowDrop(event)"
                     ondragleave="dragLeave(event)">
                    @forelse($tasksByStatus['cancelled'] as $task)
                        @include('tasks.partials.kanban-card', ['task' => $task, 'activeTimerTaskId' => $activeTimerTaskId ?? null])
                    @empty
                        <div class="kanban-empty text-center py-8 text-gray-400 dark:text-muted-foreground text-sm">
                            Nenhuma tarefa
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        @push('scripts')
        @include('tasks.partials.kanban-scripts')
        @endpush

    @else
        {{-- Table View --}}
        <x-data-table
            :createRoute="null"
            searchPlaceholder="Pesquisar tarefas..."
            :filters="['todos' => 'Todos', 'todo' => 'A Fazer', 'doing' => 'Em Progresso', 'done' => 'Concluído', 'cancelled' => 'Cancelada']"
            filterParam="status"
            :selectable="false"
            tableId="tarefasTable"
        >
            <x-slot name="toolbarLeading">@include('tasks.partials.toolbar')</x-slot>
            <x-slot name="toolbarTrailing">@include('tasks.partials.toolbar-actions')</x-slot>

            <x-slot name="head">
                <x-data-table.header>Título</x-data-table.header>
                <x-data-table.header>Projeto</x-data-table.header>
                <x-data-table.header class="data-table-th-compact">Status</x-data-table.header>
                <x-data-table.header align="right" class="data-table-th-actions">Ações</x-data-table.header>
            </x-slot>

            @forelse($tasks as $task)
                <x-data-table.row>
                    <x-data-table.title-cell :title="$task->title" />
                    <x-data-table.cell truncate>{{ $task->project?->name ?? '-' }}</x-data-table.cell>
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
            @empty
                <x-data-table.empty 
                    :colspan="4"
                    message="Nenhuma tarefa encontrada"
                    :createRoute="\App\Support\CurrentCompany::canManageProjects() ? route('tarefas.create') : null"
                    createLabel="Criar primeira tarefa"
                >
                    <x-slot name="icon">
                        <svg class="data-table-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </x-slot>
                </x-data-table.empty>
            @endforelse

            @if($tasks->hasPages())
                <x-slot name="pagination">
                    {{ $tasks->links() }}
                </x-slot>
            @endif
        </x-data-table>
    @endif
</x-app-layout>
