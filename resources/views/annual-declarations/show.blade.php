<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="page-title">Declaração Anual do MEI</h2>
            <p class="text-sm text-muted-foreground">
                Ano {{ $annualDeclaration->reference_year }}
            </p>
        </div>
    </x-slot>

    <x-ui.page-back :href="route('declaracao-anual.index')" class="mb-6" />

    <div class="space-y-6">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="card p-6">
                <h2 class="text-xl font-semibold mb-4 text-foreground">Resumo Financeiro</h2>
                <dl class="space-y-3">
                    <div class="flex justify-between items-center">
                        <dt class="text-muted-foreground">Receita Total:</dt>
                        <dd class="font-medium text-foreground">{{ $annualDeclaration->formatted_total_revenue }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-muted-foreground">Total DAS Pago:</dt>
                        <dd class="font-medium text-foreground">{{ $annualDeclaration->formatted_total_das_paid }}</dd>
                    </div>
                    <div class="flex justify-between items-center border-t border-border pt-3 mt-3">
                        <dt class="font-semibold text-foreground">Receita Líquida:</dt>
                        <dd class="font-bold text-lg text-foreground">{{ $annualDeclaration->formatted_net_revenue }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-muted-foreground">Total de Notas Fiscais:</dt>
                        <dd class="font-medium text-foreground">{{ $annualDeclaration->total_invoices }}</dd>
                    </div>
                </dl>
            </div>

            <div class="card p-6">
                <h2 class="text-xl font-semibold mb-4 text-foreground">Informações</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-muted-foreground">Ano de Referência</p>
                        <p class="font-medium text-foreground">{{ $annualDeclaration->reference_year }}</p>
                    </div>
                    @if($annualDeclaration->generated_at)
                        <div>
                            <p class="text-sm text-muted-foreground">Gerado em</p>
                            <p class="font-medium text-foreground">{{ $annualDeclaration->generated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif
                </div>

                <div class="mt-6 pt-6 border-t border-border">
                    <a href="{{ route('declaracao-anual.pdf', $annualDeclaration) }}" target="_blank" class="btn-primary w-full text-center">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Exportar PDF
                    </a>
                </div>
            </div>
        </div>

        <div class="card overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-xl font-semibold text-foreground">Notas Fiscais do Ano</h2>
            </div>
            <div>
                <table class="table">
                    <thead class="table-header">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Número</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Data</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Valor</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Tipo</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        @forelse($invoices as $invoice)
                            <tr class="table-row">
                                <td class="table-cell whitespace-nowrap font-medium text-foreground">
                                    {{ $invoice->numero }}
                                </td>
                                <td class="table-cell whitespace-nowrap text-foreground">
                                    {{ $invoice->data_emissao->format('d/m/Y') }}
                                </td>
                                <td class="table-cell whitespace-nowrap text-foreground">
                                    {{ $invoice->formatted_valor }}
                                </td>
                                <td class="table-cell whitespace-nowrap">
                                    {{ $invoice->invoice_type->label() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <p class="text-muted-foreground">Nenhuma nota fiscal encontrada</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-xl font-semibold text-foreground">DAS Pagos no Ano</h2>
            </div>
            <div>
                <table class="table">
                    <thead class="table-header">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Mês</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Vencimento</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Pagamento</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Valor</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        @forelse($dasPayments as $das)
                            <tr class="table-row">
                                <td class="table-cell whitespace-nowrap font-medium text-foreground">
                                    {{ $das->reference_month_formatted }}
                                </td>
                                <td class="table-cell whitespace-nowrap text-foreground">
                                    {{ $das->due_date->format('d/m/Y') }}
                                </td>
                                <td class="table-cell whitespace-nowrap text-foreground">
                                    {{ $das->payment_date ? $das->payment_date->format('d/m/Y') : '-' }}
                                </td>
                                <td class="table-cell whitespace-nowrap text-foreground">
                                    {{ $das->formatted_amount }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <p class="text-muted-foreground">Nenhum DAS encontrado</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
