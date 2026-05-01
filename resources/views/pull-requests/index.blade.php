<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Pull Requests</h2>
    </x-slot>

    <x-data-table
        :createRoute="route('pull-requests.create')"
        createLabel="Novo PR"
        searchPlaceholder="Pesquisar pull requests..."
        :selectable="true"
        tableId="pullRequestsTable"
    >
        <x-slot name="head">
            <!-- <x-data-table.header>Repositório</x-data-table.header> -->
            <x-data-table.header>PR #</x-data-table.header>
            <x-data-table.header>Título</x-data-table.header>
            <!-- <x-data-table.header>Tarefa</x-data-table.header> -->
            <x-data-table.header>Data</x-data-table.header>
            <x-data-table.header align="right">Ações</x-data-table.header>
        </x-slot>

        @forelse($pullRequests as $pr)
            <x-data-table.row :selectable="true">
                <!-- <x-data-table.cell class="font-medium text-gray-900 dark:text-white">
                    {{ $pr->repo }}
                </x-data-table.cell> -->
                <x-data-table.cell>
                    <x-status-badge status="#{{ $pr->pr_number }}" color="blue" />
                </x-data-table.cell>
                <x-data-table.cell>
                    <a href="{{ $pr->url }}" target="_blank" class="text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 hover:underline inline-flex items-center gap-1" title="{{ $pr->title }}" style="max-width: 300px;">
                        <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $pr->title }}</span>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                </x-data-table.cell>
                <!-- <x-data-table.cell>
                    @if($pr->task)
                        <a href="{{ route('tarefas.editar', $pr->task) }}" class="text-primary-600 dark:text-primary-400 hover:underline text-sm">
                            {{ $pr->task->title }}
                        </a>
                    @else
                        <span class="text-gray-400 dark:text-gray-600">-</span>
                    @endif
                </x-data-table.cell> -->
                <x-data-table.cell>
                    {{ $pr->work_date->format('d/m/Y') }}
                </x-data-table.cell>
                <x-data-table.actions 
                    :editRoute="route('pull-requests.edit', $pr)"
                    :deleteRoute="route('pull-requests.destroy', $pr)"
                    deleteConfirm="Tem certeza que deseja excluir este PR?"
                />
            </x-data-table.row>
        @empty
            <x-data-table.empty 
                :colspan="7"
                message="Nenhum pull request registrado"
                :createRoute="route('pull-requests.create')"
                createLabel="Criar primeiro PR"
            >
                <x-slot name="icon">
                    <svg class="data-table-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </x-slot>
            </x-data-table.empty>
        @endforelse

        @if($pullRequests->hasPages())
            <x-slot name="pagination">
                {{ $pullRequests->links() }}
            </x-slot>
        @endif
    </x-data-table>
</x-app-layout>
