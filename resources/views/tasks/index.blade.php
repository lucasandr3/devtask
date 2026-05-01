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
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
        <div class="flex items-center gap-2 p-1 rounded-lg bg-gray-100 dark:bg-neutral-800">
            <a href="{{ route('tarefas.index', ['view' => 'table']) }}" 
               onclick="saveViewPreference('table')"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md transition-all duration-200 {{ $view === 'table' ? 'bg-white dark:bg-neutral-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}"
               title="Visualização em Tabela">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
                <span class="hidden sm:inline">Tabela</span>
            </a>
            <a href="{{ route('tarefas.index', ['view' => 'kanban']) }}" 
               onclick="saveViewPreference('kanban')"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md transition-all duration-200 {{ $view === 'kanban' ? 'bg-white dark:bg-neutral-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}"
               title="Visualização Kanban">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                </svg>
                <span class="hidden sm:inline">Kanban</span>
            </a>
        </div>
        
        <a href="{{ route('tarefas.create') }}" class="btn-primary btn-responsive" title="Nova Tarefa">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="btn-text">Nova Tarefa</span>
        </a>
    </div>

    @if($view === 'kanban')
        {{-- Kanban View --}}
        <div class="kanban-board grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            {{-- Coluna A Fazer --}}
            <div class="kanban-column">
                <div class="kanban-column-header bg-gray-100 dark:bg-neutral-800 rounded-t-lg px-4 py-3 border-b-2 border-gray-400">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-gray-400"></span>
                            <h3 class="font-semibold text-gray-700 dark:text-gray-300">A Fazer</h3>
                        </div>
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-200 dark:bg-neutral-700 px-2 py-1 rounded-full">
                            {{ $tasksByStatus['todo']->count() }}
                        </span>
                    </div>
                </div>
                <div class="kanban-column-body bg-gray-50 dark:bg-neutral-900 rounded-b-lg p-2 min-h-[400px]" 
                     data-status="todo" 
                     ondrop="dropTask(event)" 
                     ondragover="allowDrop(event)"
                     ondragleave="dragLeave(event)">
                    @forelse($tasksByStatus['todo'] as $task)
                        @include('tasks.partials.kanban-card', ['task' => $task])
                    @empty
                        <div class="kanban-empty text-center py-8 text-gray-400 dark:text-gray-600 text-sm">
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
                        @include('tasks.partials.kanban-card', ['task' => $task])
                    @empty
                        <div class="kanban-empty text-center py-8 text-gray-400 dark:text-gray-600 text-sm">
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
                        @include('tasks.partials.kanban-card', ['task' => $task])
                    @empty
                        <div class="kanban-empty text-center py-8 text-gray-400 dark:text-gray-600 text-sm">
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
                        @include('tasks.partials.kanban-card', ['task' => $task])
                    @empty
                        <div class="kanban-empty text-center py-8 text-gray-400 dark:text-gray-600 text-sm">
                            Nenhuma tarefa
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            function allowDrop(event) {
                event.preventDefault();
                event.currentTarget.classList.add('ring-2', 'ring-primary-500', 'ring-opacity-50');
            }

            function dragLeave(event) {
                event.currentTarget.classList.remove('ring-2', 'ring-primary-500', 'ring-opacity-50');
            }

            function dragStart(event) {
                event.dataTransfer.setData('taskId', event.target.dataset.taskId);
                event.target.classList.add('opacity-50');
            }

            function dragEnd(event) {
                event.target.classList.remove('opacity-50');
            }

            async function dropTask(event) {
                event.preventDefault();
                event.currentTarget.classList.remove('ring-2', 'ring-primary-500', 'ring-opacity-50');
                
                const taskId = event.dataTransfer.getData('taskId');
                const newStatus = event.currentTarget.dataset.status;
                const taskCard = document.querySelector(`[data-task-id="${taskId}"]`);
                
                if (!taskCard) return;

                // Move o card visualmente
                const emptyMessage = event.currentTarget.querySelector('.kanban-empty');
                if (emptyMessage) {
                    emptyMessage.remove();
                }
                event.currentTarget.appendChild(taskCard);

                // Atualiza no servidor
                try {
                    const response = await fetch(`/tarefas/${taskId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ status: newStatus })
                    });

                    if (!response.ok) {
                        throw new Error('Erro ao atualizar status');
                    }

                    // Atualiza contadores
                    updateColumnCounts();
                    
                } catch (error) {
                    console.error('Erro:', error);
                    // Recarrega a página em caso de erro
                    window.location.reload();
                }
            }

            function updateColumnCounts() {
                document.querySelectorAll('.kanban-column-body').forEach(column => {
                    const count = column.querySelectorAll('.kanban-card').length;
                    const header = column.previousElementSibling;
                    const countBadge = header.querySelector('span:last-child');
                    if (countBadge) {
                        countBadge.textContent = count;
                    }
                    
                    // Adiciona mensagem de vazio se necessário
                    const hasEmpty = column.querySelector('.kanban-empty');
                    if (count === 0 && !hasEmpty) {
                        const emptyDiv = document.createElement('div');
                        emptyDiv.className = 'kanban-empty text-center py-8 text-gray-400 dark:text-gray-600 text-sm';
                        emptyDiv.textContent = 'Nenhuma tarefa';
                        column.appendChild(emptyDiv);
                    }
                });
            }
        </script>
        @endpush

    @else
        {{-- Table View --}}
        <x-data-table
            :createRoute="null"
            searchPlaceholder="Pesquisar tarefas..."
            :filters="['todos' => 'Todos', 'todo' => 'A Fazer', 'doing' => 'Em Progresso', 'done' => 'Concluído', 'cancelled' => 'Cancelada']"
            filterParam="status"
            :selectable="true"
            tableId="tarefasTable"
        >
            <x-slot name="head">
                <x-data-table.header>Título</x-data-table.header>
                <x-data-table.header>Data</x-data-table.header>
                <x-data-table.header>Status</x-data-table.header>
                <x-data-table.header>PRs</x-data-table.header>
                <x-data-table.header align="right">Ações</x-data-table.header>
            </x-slot>

            @forelse($tasks as $task)
                <x-data-table.row :selectable="true">
                    <x-data-table.title-cell 
                        :title="$task->title" 
                        :subtitle="$task->description" 
                    />
                    <x-data-table.cell>
                        {{ $task->work_date->format('d/m/Y') }}
                    </x-data-table.cell>
                    <x-data-table.cell>
                        <x-status-badge :status="$task->status->label()" :color="$task->status->color()" />
                    </x-data-table.cell>
                    <x-data-table.cell>
                        @if($task->pull_requests_count > 0)
                            <span class="inline-flex items-center gap-1 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $task->pull_requests_count }}
                            </span>
                        @else
                            <span class="text-gray-400 dark:text-gray-600">-</span>
                        @endif
                    </x-data-table.cell>
                    <x-data-table.actions 
                        :editRoute="route('tarefas.editar', $task)"
                        :deleteRoute="route('tarefas.destroy', $task)"
                        deleteConfirm="Tem certeza que deseja excluir esta tarefa?"
                    />
                </x-data-table.row>
            @empty
                <x-data-table.empty 
                    :colspan="5"
                    message="Nenhuma tarefa encontrada"
                    :createRoute="route('tarefas.create')"
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
