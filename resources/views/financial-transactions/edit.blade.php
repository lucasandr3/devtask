<x-app-layout>
    <x-slot name="header"><h2 class="page-title">Editar lançamento</h2></x-slot>
    <x-ui.page-back :fallback="route('financeiro.lancamentos.index')" class="mb-6" />

    @if($transaction->isInstallment() && $siblings->count() > 1)
        <div class="card p-5 mb-6 w-full">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-4">
                <div>
                    <h3 class="text-base font-semibold text-foreground">Parcelamento {{ $transaction->installment_count }}x</h3>
                    <p class="text-sm text-muted-foreground mt-0.5">Você está editando apenas a parcela {{ $transaction->installment_number }}/{{ $transaction->installment_count }}.</p>
                </div>
                @if(\App\Support\CurrentCompany::canManageFinance())
                    <form method="POST" action="{{ route('financeiro.lancamentos.destroy', $transaction) }}" onsubmit="return confirm('Excluir todas as {{ $siblings->count() }} parcelas deste lançamento?');">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="delete_group" value="1">
                        <button type="submit" class="text-sm font-medium text-red-600 dark:text-red-400 hover:underline">Excluir todas as parcelas</button>
                    </form>
                @endif
            </div>
            <div class="overflow-x-auto rounded-lg border border-border">
                <table class="table text-sm">
                    <thead class="table-header">
                        <tr>
                            <th class="px-4 py-2 text-left">Parcela</th>
                            <th class="px-4 py-2 text-right">Valor</th>
                            <th class="px-4 py-2 text-left">Vencimento</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-right">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        @foreach($siblings as $sibling)
                            <tr @class(['table-row', 'bg-primary/5' => $sibling->id === $transaction->id])>
                                <td class="table-cell">{{ $sibling->installment_number }}/{{ $sibling->installment_count }}</td>
                                <td class="table-cell text-right tabular-nums">{{ $sibling->formatted_amount }}</td>
                                <td class="table-cell">{{ $sibling->due_date->format('d/m/Y') }}</td>
                                <td class="table-cell">{{ $sibling->status->label() }}</td>
                                <td class="table-cell text-right">
                                    @if($sibling->id !== $transaction->id)
                                        <a href="{{ route_preserve_return('financeiro.lancamentos.edit', $sibling) }}" class="text-primary text-sm hover:underline">Editar</a>
                                    @else
                                        <span class="text-muted-foreground text-sm">Atual</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="text-xs text-muted-foreground mt-3">
                Total do grupo: <strong>R$ {{ number_format($siblings->sum('amount'), 2, ',', '.') }}</strong>
            </p>
        </div>
    @endif

    <div class="card p-6 w-full">
        <form method="POST" action="{{ route('financeiro.lancamentos.update', $transaction) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('financial-transactions._form', ['transaction' => $transaction, 'type' => $transaction->type->value])
            <div class="flex justify-end gap-3 pt-4 border-t border-border">
                <a href="{{ back_url(route('financeiro.lancamentos.index')) }}" class="btn-secondary">Cancelar</a>
                <x-primary-button>{{ $transaction->isInstallment() ? 'Salvar parcela' : 'Salvar' }}</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
