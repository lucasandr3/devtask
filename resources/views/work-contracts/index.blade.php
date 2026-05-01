<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Contratos de Trabalho</h2>
    </x-slot>

    <x-data-table
        :createRoute="route('contratos.create')"
        createLabel="Novo Contrato"
        searchPlaceholder="Pesquisar contratos..."
        :selectable="true"
        tableId="contratosTable"
    >
        <x-slot name="head">
            <x-data-table.header>Empresa</x-data-table.header>
            <x-data-table.header>Valor</x-data-table.header>
            <x-data-table.header>Início</x-data-table.header>
            <x-data-table.header>Fim</x-data-table.header>
            <x-data-table.header>Horas Mensais</x-data-table.header>
            <x-data-table.header align="right">Ações</x-data-table.header>
        </x-slot>

        @forelse($contracts as $contract)
            <x-data-table.row :selectable="true">
                <x-data-table.cell class="font-medium text-gray-900 dark:text-white">
                    {{ $contract->company_name ?? '-' }}
                </x-data-table.cell>
                <x-data-table.cell>
                    @if($contract->contract_value)
                        <span class="data-table-value">R$ {{ number_format($contract->contract_value, 2, ',', '.') }}</span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </x-data-table.cell>
                <x-data-table.cell class="font-medium text-gray-900 dark:text-white">
                    {{ $contract->start_date->format('d/m/Y') }}
                </x-data-table.cell>
                <x-data-table.cell>
                    @if($contract->end_date)
                        {{ $contract->end_date->format('d/m/Y') }}
                    @else
                        <x-status-badge status="Ativo" color="green" />
                    @endif
                </x-data-table.cell>
                <x-data-table.cell>
                    @php
                        $hours = round($contract->monthly_minutes / 60, 2);
                        $hoursFormatted = number_format($hours, $hours == floor($hours) ? 0 : 2, ',', '.');
                    @endphp
                    <span class="data-table-value">{{ $hoursFormatted }} hora{{ $hours != 1 ? 's' : '' }}</span>
                </x-data-table.cell>
                <x-data-table.actions 
                    :editRoute="route('contratos.edit', $contract)"
                    :deleteRoute="route('contratos.destroy', $contract)"
                    deleteConfirm="Tem certeza que deseja excluir este contrato?"
                />
            </x-data-table.row>
        @empty
            <x-data-table.empty 
                :colspan="7"
                message="Nenhum contrato cadastrado"
                :createRoute="route('contratos.create')"
                createLabel="Criar primeiro contrato"
            >
                <x-slot name="icon">
                    <svg class="data-table-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </x-slot>
            </x-data-table.empty>
        @endforelse
    </x-data-table>
</x-app-layout>
