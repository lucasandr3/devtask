<div class="flex flex-wrap items-center gap-2">
    <div class="toggle-group">
        <x-ui.toggle-item
            :href="route('tarefas.index', array_filter(['view' => 'table', 'project_id' => request('project_id')]))"
            :active="$view === 'table'"
            label="Visualização em tabela"
            onclick="saveViewPreference('table')"
        >
            <x-ui.icon name="table" />
            <span class="hidden sm:inline">Tabela</span>
        </x-ui.toggle-item>
        <x-ui.toggle-item
            :href="route('tarefas.index', array_filter(['view' => 'kanban', 'project_id' => request('project_id')]))"
            :active="$view === 'kanban'"
            label="Visualização kanban"
            onclick="saveViewPreference('kanban')"
        >
            <x-ui.icon name="kanban" />
            <span class="hidden sm:inline">Kanban</span>
        </x-ui.toggle-item>
    </div>

    @if(\App\Support\CurrentCompany::canManageProjects())
        <a href="{{ route('tarefas.create') }}" class="btn-primary h-9 px-3 ui-tooltip ui-tooltip-top" data-tooltip="Nova tarefa" aria-label="Nova tarefa">
            <x-ui.icon name="plus" />
            <span class="hidden sm:inline">Nova Tarefa</span>
        </a>
    @endif
</div>
