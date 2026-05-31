@php
    $totalIn = collect($cashFlow['inflows'])->sum('amount');
    $totalOut = collect($cashFlow['outflows'])->sum('amount');
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Gestão Financeira</h2>
    </x-slot>

    <div
        id="finance-charts-root"
        class="space-y-8 w-full"
        data-trend='@json($trendChart)'
        data-composition='@json($compositionChart)'
        data-cash='@json($cashFlowChart)'
    >
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="min-w-0">
                <p class="text-lg font-semibold text-foreground tracking-tight">{{ $monthLabel }}</p>
                <p class="text-sm text-muted-foreground mt-0.5 truncate">{{ \App\Support\CurrentCompany::get()?->name }}</p>
            </div>
            <form method="GET" action="{{ route('financeiro.index') }}" class="flex items-center gap-2 shrink-0">
                <label for="finance-month" class="sr-only">Período</label>
                <input type="text" id="finance-month" name="month" value="{{ $month }}" class="select-input w-44 sm:w-52" data-monthpicker placeholder="Selecione o período" onchange="this.form.submit()">
            </form>
        </div>

        @if($alerts->isNotEmpty())
            <div class="rounded-2xl border border-amber-200/80 dark:border-amber-800/40 bg-gradient-to-br from-amber-50/80 to-orange-50/30 dark:from-amber-950/30 dark:to-orange-950/10 p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <h3 class="text-base font-semibold text-foreground flex items-center gap-2">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-500/15 text-amber-600 dark:text-amber-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </span>
                            Contas e vencimentos
                            <span class="text-sm font-normal text-muted-foreground">({{ $alerts->count() }})</span>
                        </h3>
                        <p class="text-sm text-muted-foreground mt-1 ml-10">Próximos {{ \App\Services\FinancialAlertService::DUE_SOON_DAYS }} dias e itens em atraso</p>
                    </div>
                    <a href="{{ route_with_return('financeiro.lancamentos.index') }}" class="text-sm font-medium text-primary hover:underline shrink-0">Ver lançamentos</a>
                </div>
                <ul class="space-y-2 max-h-56 overflow-y-auto">
                    @foreach($alerts->take(6) as $alert)
                        <li>
                            <a href="{{ $alert['url'] }}" @class([
                                'flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-4 py-3 rounded-xl border transition-all',
                                'border-red-200/80 bg-white/90 dark:bg-red-950/20 dark:border-red-900/50 hover:shadow-sm' => $alert['severity'] === 'danger',
                                'border-border/80 bg-card/80 hover:border-primary/30 hover:shadow-sm' => $alert['severity'] === 'warning',
                            ])>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-foreground">{{ $alert['title'] }}</p>
                                    <p class="text-xs text-muted-foreground mt-0.5 truncate">{{ $alert['message'] }}</p>
                                </div>
                                <span @class([
                                    'text-sm font-semibold shrink-0',
                                    'text-red-600 dark:text-red-400' => $alert['severity'] === 'danger',
                                    'text-amber-600 dark:text-amber-400' => $alert['severity'] === 'warning',
                                ])>{{ $alert['formatted_amount'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- KPIs --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="group rounded-2xl border border-border bg-card p-5 shadow-sm transition-all hover:border-emerald-500/30 hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="rounded-xl bg-emerald-500/10 p-2.5">
                        <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:text-emerald-300">Entradas</span>
                </div>
                <p class="mt-4 text-2xl font-bold tracking-tight text-emerald-600 dark:text-emerald-400">{{ $financialData['formatted_total_inflows'] }}</p>
                <p class="mt-1 text-sm text-muted-foreground">
                    NF {{ $financialData['formatted_total_revenue'] }}
                    @if(($financialData['total_receivable_paid'] ?? 0) > 0)
                        · recebíveis pagos {{ $financialData['formatted_total_receivable_paid'] }}
                    @endif
                </p>
            </div>

            <div class="group rounded-2xl border border-border bg-card p-5 shadow-sm transition-all hover:border-rose-500/30 hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="rounded-xl bg-rose-500/10 p-2.5">
                        <svg class="h-5 w-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <span class="rounded-full bg-rose-500/10 px-2.5 py-0.5 text-xs font-medium text-rose-700 dark:text-rose-300">Saídas</span>
                </div>
                <p class="mt-4 text-2xl font-bold tracking-tight text-rose-600 dark:text-rose-400">{{ $financialData['formatted_total_expenses'] }}</p>
                <p class="mt-1 text-sm text-muted-foreground">
                    Tributos {{ $financialData['formatted_total_das_paid'] }}
                    @if(($financialData['total_payable_paid'] ?? 0) > 0)
                        · contas pagas {{ $financialData['formatted_total_payable_paid'] }}
                    @endif
                </p>
            </div>

            <div class="group rounded-2xl border border-border bg-card p-5 shadow-sm transition-all hover:border-amber-500/30 hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="rounded-xl bg-amber-500/10 p-2.5">
                        <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="rounded-full bg-amber-500/10 px-2.5 py-0.5 text-xs font-medium text-amber-700 dark:text-amber-300">A pagar</span>
                </div>
                <p class="mt-4 text-2xl font-bold tracking-tight text-amber-600 dark:text-amber-400">{{ $financialData['formatted_total_pending_out'] }}</p>
                <p class="mt-1 text-sm text-muted-foreground">
                    Tributos e contas a pagar em aberto
                    @if(($financialData['total_payable_pending'] ?? 0) > 0)
                        (lançamentos {{ $financialData['formatted_total_payable_pending'] }})
                    @endif
                </p>
            </div>

            <div class="group rounded-2xl border border-border bg-card p-5 shadow-sm transition-all hover:border-indigo-500/30 hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="rounded-xl bg-indigo-500/10 p-2.5">
                        <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <span class="rounded-full bg-indigo-500/10 px-2.5 py-0.5 text-xs font-medium text-indigo-700 dark:text-indigo-300">Resultado</span>
                </div>
                <p class="mt-4 text-2xl font-bold tracking-tight {{ $financialData['balance'] >= 0 ? 'text-indigo-600 dark:text-indigo-400' : 'text-red-600 dark:text-red-400' }}">{{ $financialData['formatted_balance'] }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Entradas realizadas − saídas realizadas</p>
                @if(($financialData['total_receivable_open'] ?? 0) > 0)
                    <p class="mt-1 text-xs text-muted-foreground">A receber em aberto: {{ $financialData['formatted_total_receivable_open'] }} (não entra no resultado)</p>
                @endif
            </div>
        </div>

        {{-- Gráficos --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 rounded-2xl border border-border bg-card p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-foreground">Desempenho financeiro</h3>
                        <p class="text-sm text-muted-foreground mt-0.5">Últimos 6 meses · entradas, saídas e resultado</p>
                    </div>
                </div>
                <div class="relative h-[320px]">
                    <div id="financeTrendEmpty" class="hidden absolute inset-0 flex flex-col items-center justify-center text-center px-6">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-muted/50 text-muted-foreground mb-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <p class="text-sm font-medium text-foreground">Sem movimentação no período</p>
                        <p class="text-xs text-muted-foreground mt-1 max-w-xs">Cadastre faturamento, lançamentos ou tributos para visualizar a evolução.</p>
                    </div>
                    <canvas id="financeTrendChart" class="w-full h-full" aria-label="Gráfico de desempenho financeiro"></canvas>
                </div>
            </div>

            <div class="rounded-2xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-foreground">Posição do mês</h3>
                    <p class="text-sm text-muted-foreground mt-0.5">{{ $monthLabel }}</p>
                </div>
                <div class="relative h-[320px]">
                    <div id="financeCompositionEmpty" class="hidden absolute inset-0 flex flex-col items-center justify-center text-center px-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-muted/50 text-muted-foreground mb-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                        </div>
                        <p class="text-sm font-medium text-foreground">Nenhum valor registrado</p>
                        <p class="text-xs text-muted-foreground mt-1">A distribuição aparece quando houver lançamentos no mês.</p>
                    </div>
                    <canvas id="financeCompositionChart" class="w-full h-full" aria-label="Distribuição financeira do mês"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-2xl border border-border bg-card p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-foreground">Fluxo do período</h3>
                <p class="text-sm text-muted-foreground mt-0.5 mb-6">Comparativo de entradas e saídas por categoria</p>
                <div class="relative h-56">
                    @if($totalIn + $totalOut > 0)
                        <canvas id="financeCashChart" class="w-full h-full" aria-label="Fluxo por categoria"></canvas>
                    @else
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center text-muted-foreground">
                            <p class="text-sm">Sem dados de fluxo neste mês</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="rounded-2xl border border-border bg-card p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-foreground mb-4">Resumo do fluxo</h3>
                <div class="space-y-5">
                    <div>
                        <div class="flex justify-between text-xs font-semibold uppercase tracking-wide text-muted-foreground mb-3">
                            <span>Entradas</span>
                            <span class="text-emerald-600 dark:text-emerald-400">R$ {{ number_format($totalIn, 2, ',', '.') }}</span>
                        </div>
                        <ul class="space-y-2.5">
                            @foreach($cashFlow['inflows'] as $item)
                                <li class="flex justify-between text-sm">
                                    <span class="text-muted-foreground">{{ $item['label'] }}</span>
                                    <span class="font-medium tabular-nums">R$ {{ number_format($item['amount'], 2, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="border-t border-border pt-5">
                        <div class="flex justify-between text-xs font-semibold uppercase tracking-wide text-muted-foreground mb-3">
                            <span>Saídas</span>
                            <span class="text-rose-600 dark:text-rose-400">R$ {{ number_format($totalOut, 2, ',', '.') }}</span>
                        </div>
                        <ul class="space-y-2.5">
                            @foreach($cashFlow['outflows'] as $item)
                                <li class="flex justify-between text-sm">
                                    <span class="text-muted-foreground">{{ $item['label'] }}</span>
                                    <span class="font-medium tabular-nums">R$ {{ number_format($item['amount'], 2, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        @if(\App\Support\CurrentCompany::canManageFinance())
            <div class="flex flex-wrap items-center justify-between gap-2 w-full">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route_with_return('notas-fiscais.create') }}" class="inline-flex items-center gap-2 rounded-xl border border-border bg-card px-4 py-2.5 text-sm font-medium shadow-sm hover:bg-accent transition-colors">+ Faturamento</a>
                    <a href="{{ route_with_return('financeiro.lancamentos.create', ['type' => 'payable']) }}" class="inline-flex items-center gap-2 rounded-xl border border-border bg-card px-4 py-2.5 text-sm font-medium shadow-sm hover:bg-accent transition-colors">+ Conta a pagar</a>
                    <a href="{{ route_with_return('financeiro.lancamentos.create', ['type' => 'receivable']) }}" class="inline-flex items-center gap-2 rounded-xl border border-border bg-card px-4 py-2.5 text-sm font-medium shadow-sm hover:bg-accent transition-colors">+ Conta a receber</a>
                    <a href="{{ route_with_return('das.create') }}" class="inline-flex items-center gap-2 rounded-xl border border-border bg-card px-4 py-2.5 text-sm font-medium shadow-sm hover:bg-accent transition-colors">+ Guia tributária</a>
                    <a href="{{ route_with_return('clientes.create') }}" class="inline-flex items-center gap-2 rounded-xl border border-border bg-card px-4 py-2.5 text-sm font-medium shadow-sm hover:bg-accent transition-colors">+ Cliente</a>
                </div>
                <a href="{{ route_with_return('relatorios.financeiro', ['month' => $month]) }}" class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-medium text-primary-foreground shadow-sm hover:bg-primary/90 transition-colors shrink-0">Relatório completo</a>
            </div>
        @endif

        <div class="rounded-2xl border border-border bg-card overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-border bg-muted/30">
                <h3 class="font-semibold text-foreground">Rentabilidade por projeto</h3>
                <p class="text-sm text-muted-foreground mt-0.5">Horas registradas × taxa de faturamento</p>
            </div>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="table-header">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">Projeto</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-muted-foreground">Horas</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-muted-foreground">Valor faturável</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-muted-foreground">Orçamento</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        @forelse($projectMargins as $row)
                            <tr class="table-row">
                                <td class="table-cell font-medium">{{ $row['name'] }}</td>
                                <td class="table-cell text-right tabular-nums">{{ $row['hours_formatted'] }}</td>
                                <td class="table-cell text-right font-medium tabular-nums text-emerald-600 dark:text-emerald-400">{{ $row['formatted_billable'] }}</td>
                                <td class="table-cell text-right tabular-nums">{{ $row['formatted_budget'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-muted-foreground">Nenhuma hora registrada neste mês.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/finance-charts.js'])
    @endpush
</x-app-layout>
