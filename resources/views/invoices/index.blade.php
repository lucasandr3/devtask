<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Notas Fiscais</h2>
    </x-slot>

    <x-data-table
        :createRoute="route('notas-fiscais.create')"
        createLabel="Nova Nota Fiscal"
        searchPlaceholder="Pesquisar notas fiscais..."
        :selectable="true"
        tableId="notasFiscaisTable"
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
            <x-data-table.header>Número</x-data-table.header>
            <x-data-table.header>Série</x-data-table.header>
            <x-data-table.header>Data Emissão</x-data-table.header>
            <x-data-table.header>Valor</x-data-table.header>
            <x-data-table.header>Arquivo</x-data-table.header>
            <x-data-table.header align="right">Ações</x-data-table.header>
        </x-slot>

        @forelse($invoices as $invoice)
            <x-data-table.row :selectable="true">
                <x-data-table.cell class="font-medium text-gray-900 dark:text-white">
                    {{ $invoice->numero }}
                </x-data-table.cell>
                <x-data-table.cell class="text-gray-600 dark:text-gray-400">
                    {{ $invoice->serie }}
                </x-data-table.cell>
                <x-data-table.cell class="font-medium text-gray-900 dark:text-white">
                    {{ $invoice->data_emissao->format('d/m/Y') }}
                </x-data-table.cell>
                <x-data-table.cell>
                    <span class="data-table-value">R$ {{ number_format($invoice->valor, 2, ',', '.') }}</span>
                </x-data-table.cell>
                <x-data-table.cell>
                    @if($invoice->arquivo)
                        <x-status-badge status="Disponível" color="green" />
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </x-data-table.cell>
                <x-data-table.actions 
                    :editRoute="route('notas-fiscais.edit', $invoice)"
                    :deleteRoute="route('notas-fiscais.destroy', $invoice)"
                    deleteConfirm="Tem certeza que deseja excluir esta nota fiscal?"
                >
                    @if($invoice->arquivo)
                        <a href="{{ route('notas-fiscais.visualizar', $invoice) }}" target="_blank" class="action-btn view" title="Visualizar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('notas-fiscais.download', $invoice) }}" class="action-btn view" title="Baixar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                        </a>
                    @endif
                </x-data-table.actions>
            </x-data-table.row>
        @empty
            <x-data-table.empty 
                :colspan="7"
                message="Nenhuma nota fiscal cadastrada"
                :createRoute="route('notas-fiscais.create')"
                createLabel="Criar primeira nota fiscal"
            >
                <x-slot name="icon">
                    <svg class="data-table-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </x-slot>
            </x-data-table.empty>
        @endforelse

        @if($invoices->hasPages())
            <x-slot name="pagination">
                {{ $invoices->links() }}
            </x-slot>
        @endif
    </x-data-table>

    {{-- Modal de Filtros Avançados --}}
    <div id="filtrosAvancados" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="document.getElementById('filtrosAvancados').classList.add('hidden')"></div>
            <div class="relative bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-md w-full p-6 border border-gray-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filtros Avançados</h3>
                <form method="GET" action="{{ route('notas-fiscais.index') }}" class="space-y-4">
                    <div>
                        <x-input-label for="numero" value="Número" />
                        <x-text-input type="text" name="numero" id="numero" value="{{ request('numero') }}" class="mt-1 w-full" placeholder="Buscar por número" />
                    </div>
                    <div>
                        <x-input-label for="data_inicio" value="Data Início" />
                        <x-text-input type="text" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}" class="mt-1 w-full" data-datepicker-filter placeholder="Selecione a data" />
                    </div>
                    <div>
                        <x-input-label for="data_fim" value="Data Fim" />
                        <x-text-input type="text" name="data_fim" id="data_fim" value="{{ request('data_fim') }}" class="mt-1 w-full" data-datepicker-filter placeholder="Selecione a data" />
                    </div>
                    <div class="flex gap-2 pt-4">
                        <x-primary-button type="submit">Filtrar</x-primary-button>
                        @if(request()->hasAny(['numero', 'data_inicio', 'data_fim']))
                            <a href="{{ route('notas-fiscais.index') }}" class="btn-secondary">Limpar</a>
                        @endif
                        <button type="button" onclick="document.getElementById('filtrosAvancados').classList.add('hidden')" class="btn-secondary ml-auto">Fechar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
