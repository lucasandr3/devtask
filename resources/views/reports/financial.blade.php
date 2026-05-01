<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('relatorios.index') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="page-title">Relatório Financeiro</h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        {{-- Filtros e Ações --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-4">
            <div class="flex flex-wrap gap-4 items-end justify-between">
                {{-- Filtro de Período --}}
                <form method="GET" action="{{ route('relatorios.financeiro') }}" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Período</label>
                        <input type="text" name="month" id="month" value="{{ $month }}" class="input" data-monthpicker placeholder="Selecione o mês">
                    </div>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filtrar
                    </button>
                </form>

                {{-- Ações --}}
                <div class="flex flex-wrap gap-4 items-end">
                    {{-- Gerar PDF --}}
                    <a href="{{ route('relatorios.financeiro.pdf', ['month' => $month]) }}" target="_blank" class="btn-secondary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Gerar PDF
                    </a>

                    {{-- Enviar por Email --}}
                    <form method="POST" action="{{ route('relatorios.financeiro.enviar-email') }}" class="flex flex-wrap gap-2 items-end">
                        @csrf
                        <input type="hidden" name="month" value="{{ $month }}">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Enviar para</label>
                            <input type="email" name="email" id="email" class="input" placeholder="email@exemplo.com" required>
                        </div>
                        <button type="submit" class="btn-secondary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Enviar por Email
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Cards de Resumo --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Receita Total --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl shrink-0">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Receita Total</p>
                        <p class="font-bold text-green-600 dark:text-green-400" style="font-size: 19px;">{{ $financialData['formatted_total_revenue'] }}</p>
                    </div>
                </div>
            </div>

            {{-- DAS Pago --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl shrink-0">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-gray-500 dark:text-gray-400">DAS Pago</p>
                        <p class="font-bold text-blue-600 dark:text-blue-400" style="font-size: 19px;">{{ $financialData['formatted_total_das_paid'] }}</p>
                    </div>
                </div>
            </div>

            {{-- DAS Pendente --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-amber-100 dark:bg-amber-900/30 rounded-xl shrink-0">
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-gray-500 dark:text-gray-400">DAS Pendente</p>
                        <p class="font-bold text-amber-600 dark:text-amber-400" style="font-size: 19px;">{{ $financialData['formatted_total_das_pending'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Saldo --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 {{ $financialData['balance'] >= 0 ? 'bg-purple-100 dark:bg-purple-900/30' : 'bg-red-100 dark:bg-red-900/30' }} rounded-xl shrink-0">
                        <svg class="w-6 h-6 {{ $financialData['balance'] >= 0 ? 'text-purple-600 dark:text-purple-400' : 'text-red-600 dark:text-red-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Saldo</p>
                        <p class="font-bold {{ $financialData['balance'] >= 0 ? 'text-purple-600 dark:text-purple-400' : 'text-red-600 dark:text-red-400' }}" style="font-size: 19px;">{{ $financialData['formatted_balance'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notas Fiscais --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Notas Fiscais do Período</h3>
                <span class="px-3 py-1 text-sm font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full">
                    {{ $financialData['invoices']->count() }} {{ $financialData['invoices']->count() === 1 ? 'nota' : 'notas' }}
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-slate-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Número</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Data Emissão</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Descrição</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Valor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                        @forelse($financialData['invoices'] as $invoice)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $invoice->numero }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $invoice->data_emissao->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ Str::limit($invoice->descricao, 50) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600 dark:text-green-400 text-right">
                                    R$ {{ number_format($invoice->valor, 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400">Nenhuma nota fiscal encontrada para este período.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($financialData['invoices']->count() > 0)
                        <tfoot class="bg-gray-50 dark:bg-slate-700/50">
                            <tr>
                                <td colspan="3" class="px-6 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Total:
                                </td>
                                <td class="px-6 py-3 text-right text-sm font-bold text-green-600 dark:text-green-400">
                                    {{ $financialData['formatted_total_revenue'] }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        {{-- Pagamentos DAS --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pagamentos DAS</h3>
                <span class="px-3 py-1 text-sm font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full">
                    {{ $financialData['das_payments']->count() }} {{ $financialData['das_payments']->count() === 1 ? 'guia' : 'guias' }}
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-slate-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Referência</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Vencimento</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Valor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                        @forelse($financialData['das_payments'] as $das)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $das->reference_month->format('m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $das->due_date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-status-badge :status="$das->status->label()" :color="$das->status->color()" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white text-right">
                                    R$ {{ number_format($das->amount, 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400">Nenhum DAS encontrado para este período.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
