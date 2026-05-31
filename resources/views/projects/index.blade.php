<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Projetos</h2>
    </x-slot>

    <x-data-table
        :createRoute="\App\Support\CurrentCompany::canManageProjects() ? route('projetos.create') : null"
        createLabel="Novo Projeto"
        searchPlaceholder="Pesquisar projetos..."
        :selectable="false"
        tableId="projectsTable"
    >
        <x-slot name="head">
            <x-data-table.header>Projeto</x-data-table.header>
            <x-data-table.header class="data-table-th-compact">Status</x-data-table.header>
            <x-data-table.header class="data-table-th-compact">Tarefas</x-data-table.header>
            <x-data-table.header align="right" class="data-table-th-actions">Ações</x-data-table.header>
        </x-slot>

        @forelse($projects as $project)
            <x-data-table.row>
                <x-data-table.cell truncate class="font-medium text-foreground">
                    <a href="{{ route('projetos.show', $project) }}" class="hover:text-primary truncate block ui-tooltip ui-tooltip-top" data-tooltip="{{ $project->name }}">
                        {{ $project->name }}
                    </a>
                </x-data-table.cell>
                <x-data-table.cell class="data-table-td-compact">
                    <x-status-badge :status="$project->status->label()" color="blue" />
                </x-data-table.cell>
                <x-data-table.cell class="data-table-td-compact">{{ $project->tasks_count }}</x-data-table.cell>
                <x-data-table.actions
                    :viewRoute="route('projetos.show', $project)"
                    :editRoute="\App\Support\CurrentCompany::canManageProjects() ? route('projetos.edit', $project) : null"
                />
            </x-data-table.row>
        @empty
            <x-data-table.empty colspan="4" message="Nenhum projeto cadastrado." />
        @endforelse
    </x-data-table>
</x-app-layout>
