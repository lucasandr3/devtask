<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Fechamento anual</h2>
    </x-slot>

    <x-ui.page-back :href="route('financeiro.index')" class="mb-6" />

    <x-data-table
        searchPlaceholder="Pesquisar declarações..."
        :selectable="false"
        tableId="annualDeclarationsTable"
    >
        {{-- Gerar Declaração --}}
        <x-slot name="actions">
            @if(\App\Support\CurrentCompany::canManageFinance())
            <form method="POST" action="{{ route('declaracao-anual.gerar') }}" class="flex flex-wrap items-center gap-2">
                @csrf
                <input type="number" name="year" value="{{ date('Y') }}" min="2020" max="{{ date('Y') }}" class="select-input w-24" placeholder="Ano">
                <button type="submit" class="btn-primary h-9 px-3 shrink-0 ui-tooltip ui-tooltip-top" data-tooltip="Gerar Declaração" aria-label="Gerar Declaração">
                    <x-ui.icon name="report" />
                    <span class="hidden sm:inline">Gerar Declaração</span>
                </button>
            </form>
            @endif
        </x-slot>

        <x-slot name="head">
            <x-data-table.header>Ano</x-data-table.header>
            <x-data-table.header>Receita Total</x-data-table.header>
            <x-data-table.header>Tributos pagos</x-data-table.header>
            <x-data-table.header>Receita Líquida</x-data-table.header>
            <x-data-table.header>Notas Fiscais</x-data-table.header>
            <x-data-table.header>Gerado em</x-data-table.header>
            <x-data-table.header align="right">Ações</x-data-table.header>
        </x-slot>

        @forelse($declarations as $declaration)
            <x-data-table.row>
                <x-data-table.cell class="font-medium text-foreground">
                    {{ $declaration->reference_year }}
                </x-data-table.cell>
                <x-data-table.cell>
                    <span class="data-table-value">{{ $declaration->formatted_total_revenue }}</span>
                </x-data-table.cell>
                <x-data-table.cell>
                    <span class="data-table-value">{{ $declaration->formatted_total_das_paid }}</span>
                </x-data-table.cell>
                <x-data-table.cell>
                    <span class="data-table-value font-semibold">{{ $declaration->formatted_net_revenue }}</span>
                </x-data-table.cell>
                <x-data-table.cell>
                    {{ $declaration->total_invoices }}
                </x-data-table.cell>
                <x-data-table.cell>
                    {{ $declaration->generated_at ? $declaration->generated_at->format('d/m/Y H:i') : '-' }}
                </x-data-table.cell>
                <td class="data-table-td text-right">
                    <div class="data-table-actions-cell">
                        <a href="{{ route('declaracao-anual.show', $declaration) }}" class="action-btn view ui-tooltip ui-tooltip-top" data-tooltip="Ver declaração" aria-label="Ver declaração">
                            <svg class="shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span class="btn-text">Ver</span>
                        </a>
                        <a href="{{ route('declaracao-anual.pdf', $declaration) }}" target="_blank" class="action-btn pdf ui-tooltip ui-tooltip-top" data-tooltip="Baixar PDF" aria-label="Baixar PDF">
                            <svg class="shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span class="btn-text">PDF</span>
                        </a>
                    </div>
                </td>
            </x-data-table.row>
        @empty
            <x-data-table.empty 
                :colspan="7"
                message="Nenhuma declaração anual gerada ainda"
            >
                <x-slot name="icon">
                    <svg class="data-table-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </x-slot>
            </x-data-table.empty>
        @endforelse

        @if($declarations->hasPages())
            <x-slot name="pagination">
                {{ $declarations->links() }}
            </x-slot>
        @endif
    </x-data-table>
</x-app-layout>
