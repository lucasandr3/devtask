<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Contas a pagar e receber</h2>
    </x-slot>

    <x-ui.page-back :href="route('financeiro.index')" class="mb-6" />

    <div class="w-full space-y-4">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('financeiro.lancamentos.index') }}" @class([
            'px-3 py-1.5 rounded-lg text-sm font-medium transition-colors',
            'bg-primary text-primary-foreground' => !request('type'),
            'bg-muted text-muted-foreground hover:bg-accent' => request('type'),
        ])>Todos</a>
        <a href="{{ route('financeiro.lancamentos.index', ['type' => 'payable']) }}" @class([
            'px-3 py-1.5 rounded-lg text-sm font-medium transition-colors',
            'bg-primary text-primary-foreground' => request('type') === 'payable',
            'bg-muted text-muted-foreground hover:bg-accent' => request('type') !== 'payable',
        ])>A pagar</a>
        <a href="{{ route('financeiro.lancamentos.index', ['type' => 'receivable']) }}" @class([
            'px-3 py-1.5 rounded-lg text-sm font-medium transition-colors',
            'bg-primary text-primary-foreground' => request('type') === 'receivable',
            'bg-muted text-muted-foreground hover:bg-accent' => request('type') !== 'receivable',
        ])>A receber</a>
    </div>

    <x-data-table
        searchPlaceholder="Pesquisar lançamentos..."
        :selectable="false"
        tableId="transactionsTable"
    >
        <x-slot name="actions">
            @if(\App\Support\CurrentCompany::canManageFinance())
                <a href="{{ route_with_return('financeiro.lancamentos.create', ['type' => 'receivable']) }}" class="btn-secondary h-9 px-3">+ A receber</a>
                <a href="{{ route_with_return('financeiro.lancamentos.create', ['type' => 'payable']) }}" class="btn-primary h-9 px-3">+ A pagar</a>
            @endif
        </x-slot>

        <x-slot name="head">
            <x-data-table.header>Descrição</x-data-table.header>
            <x-data-table.header>Tipo</x-data-table.header>
            <x-data-table.header>Valor</x-data-table.header>
            <x-data-table.header>Vencimento</x-data-table.header>
            <x-data-table.header>Status</x-data-table.header>
            @if(\App\Support\CurrentCompany::canManageFinance())
                <x-data-table.header align="right">Ações</x-data-table.header>
            @endif
        </x-slot>

        @forelse($transactions as $transaction)
            <x-data-table.row>
                <x-data-table.cell>
                    <div class="font-medium text-foreground">{{ $transaction->description }}</div>
                    @if($transaction->isInstallment())
                        <span class="inline-flex mt-1 items-center rounded-md bg-indigo-500/10 px-2 py-0.5 text-xs font-medium text-indigo-700 dark:text-indigo-300">
                            Parcela {{ $transaction->installment_label }}
                        </span>
                    @endif
                </x-data-table.cell>
                <x-data-table.cell>{{ $transaction->type->label() }}</x-data-table.cell>
                <x-data-table.cell class="tabular-nums">{{ $transaction->formatted_amount }}</x-data-table.cell>
                <x-data-table.cell>{{ $transaction->due_date->format('d/m/Y') }}</x-data-table.cell>
                <x-data-table.cell>
                    <span @class([
                        'inline-flex rounded-md px-2 py-0.5 text-xs font-medium',
                        'bg-emerald-500/10 text-emerald-700 dark:text-emerald-300' => $transaction->status->value === 'paid',
                        'bg-amber-500/10 text-amber-700 dark:text-amber-300' => $transaction->status->value === 'pending',
                        'bg-red-500/10 text-red-700 dark:text-red-300' => $transaction->status->value === 'cancelled',
                    ])>{{ $transaction->status->label() }}</span>
                </x-data-table.cell>
                @if(\App\Support\CurrentCompany::canManageFinance())
                    <x-data-table.cell align="right">
                        <x-data-table.actions
                            :editRoute="route('financeiro.lancamentos.edit', $transaction)"
                            :deleteRoute="route('financeiro.lancamentos.destroy', $transaction)"
                            :deleteConfirm="$transaction->isInstallment() ? 'Excluir apenas esta parcela? Para remover todas, use Editar → Excluir todas as parcelas.' : 'Excluir este lançamento?'"
                        />
                    </x-data-table.cell>
                @endif
            </x-data-table.row>
        @empty
            <x-data-table.empty message="Nenhum lançamento encontrado." :colspan="6" />
        @endforelse

        @if($transactions->hasPages())
            <x-slot name="footer">{{ $transactions->links() }}</x-slot>
        @endif
    </x-data-table>
    </div>
</x-app-layout>
