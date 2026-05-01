@php
use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">DAS - Documento de Arrecadação do Simples Nacional</h2>
    </x-slot>

    <x-data-table
        :createRoute="route('das.create')"
        createLabel="Novo DAS"
        searchPlaceholder="Pesquisar DAS..."
        :selectable="true"
        tableId="dasPaymentsTable"
    >
        {{-- Filtros Avançados --}}
        <x-slot name="actions">
            <button type="button" onclick="document.getElementById('filtrosAvancados').classList.toggle('hidden')" class="btn-secondary btn-responsive" title="Filtros">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <span class="btn-text">Filtros</span>
            </button>
        </x-slot>

        <x-slot name="head">
            <x-data-table.header>Mês Referência</x-data-table.header>
            <x-data-table.header>Vencimento</x-data-table.header>
            <x-data-table.header>Valor</x-data-table.header>
            <x-data-table.header>Status</x-data-table.header>
            <x-data-table.header>Pagamento</x-data-table.header>
            <x-data-table.header align="right">Ações</x-data-table.header>
        </x-slot>

        @forelse($dasPayments as $das)
            <x-data-table.row :selectable="true">
                <x-data-table.cell class="font-medium text-gray-900 dark:text-white">
                    {{ $das->reference_month_formatted }}
                </x-data-table.cell>
                <x-data-table.cell>
                    {{ $das->due_date->format('d/m/Y') }}
                </x-data-table.cell>
                <x-data-table.cell>
                    <span class="data-table-value">{{ $das->formatted_amount }}</span>
                </x-data-table.cell>
                <x-data-table.cell>
                    <x-status-badge :status="$das->status->label()" :color="$das->status->color()" />
                </x-data-table.cell>
                <x-data-table.cell>
                    @if($das->payment_date)
                        {{ $das->payment_date->format('d/m/Y') }}
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </x-data-table.cell>
                <x-data-table.actions 
                    :editRoute="route('das.edit', $das)"
                    :deleteRoute="route('das.destroy', $das)"
                    deleteConfirm="Tem certeza que deseja excluir este DAS?"
                >
                    @if($das->receipt_file)
                        <a href="{{ Storage::url('public/' . $das->receipt_file) }}" target="_blank" class="action-btn view" title="Ver Comprovante">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                    @endif
                </x-data-table.actions>
            </x-data-table.row>
        @empty
            <x-data-table.empty 
                :colspan="6"
                message="Nenhum DAS cadastrado"
                :createRoute="route('das.create')"
                createLabel="Cadastrar primeiro DAS"
            >
                <x-slot name="icon">
                    <svg class="data-table-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </x-slot>
            </x-data-table.empty>
        @endforelse

        @if($dasPayments->hasPages())
            <x-slot name="pagination">
                {{ $dasPayments->links() }}
            </x-slot>
        @endif
    </x-data-table>

    {{-- Modal de Filtros Avançados --}}
    <div id="filtrosAvancados" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="document.getElementById('filtrosAvancados').classList.add('hidden')"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filtros Avançados</h3>
                <form method="GET" action="{{ route('das.index') }}" class="space-y-4">
                    <div>
                        <x-input-label for="month" value="Mês" />
                        <x-text-input type="text" name="month" id="month" value="{{ request('month') }}" class="mt-1 w-full" data-monthpicker placeholder="Selecione o mês" />
                    </div>
                    <div>
                        <x-input-label for="status" value="Status" />
                        <select name="status" id="status" class="input mt-1 w-full">
                            <option value="">Todos</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Pago</option>
                            <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Vencido</option>
                        </select>
                    </div>
                    <div class="flex gap-2 pt-4">
                        <x-primary-button type="submit">Filtrar</x-primary-button>
                        @if(request()->hasAny(['month', 'status']))
                            <a href="{{ route('das.index') }}" class="btn-secondary">Limpar</a>
                        @endif
                        <button type="button" onclick="document.getElementById('filtrosAvancados').classList.add('hidden')" class="btn-secondary ml-auto">Fechar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
