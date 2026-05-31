<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Relatório Mensal</h2>
    </x-slot>

    <x-ui.page-back :href="route('painel')" class="mb-6" />

    <div class="space-y-6 w-full">
        {{-- Filtros e Ações --}}
        <div class="bg-card rounded-xl shadow-sm border border-border p-4">
            <div class="flex flex-wrap gap-4 items-center justify-between">
                {{-- Filtro de Período --}}
                <form method="GET" action="{{ route('relatorios-mensais.index') }}" class="flex flex-wrap items-center gap-2">
                    <input type="text" name="month" id="month" value="{{ $month }}" class="select-input w-40 sm:w-44 shrink-0" data-monthpicker placeholder="Selecione o mês">
                    <button type="submit" class="btn-primary shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filtrar
                    </button>
                </form>

                {{-- Ações --}}
                <div class="flex flex-wrap gap-3 items-center">
                    @if($report)
                        {{-- PDF Completo --}}
                        <a href="{{ route('relatorios-mensais.pdf', $report) }}" target="_blank" class="btn-secondary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            PDF Completo
                        </a>

                        {{-- Espelho de Horas --}}
                        <a href="{{ route('relatorios-mensais.espelho-horas', $report) }}" target="_blank" class="btn-secondary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Espelho de Horas
                        </a>
                    @else
                        {{-- Gerar Relatório --}}
                        <form method="POST" action="{{ route('relatorios-mensais.gerar') }}" class="inline">
                            @csrf
                            <input type="hidden" name="month" value="{{ $month }}">
                            <button type="submit" class="btn-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Gerar Relatório
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- Cards de Resumo --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Horas Contratadas --}}
            <div class="bg-card rounded-xl shadow-sm border border-border p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl shrink-0">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-muted-foreground">Horas Contratadas</p>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ minutesToHours($contractMinutes) }}</p>
                    </div>
                </div>
            </div>

            {{-- Total Trabalhado --}}
            <div class="bg-card rounded-xl shadow-sm border border-border p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl shrink-0">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-muted-foreground">Total Trabalhado</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ minutesToHours($totalMinutes) }}</p>
                    </div>
                </div>
            </div>

            {{-- Horas Extras --}}
            <div class="bg-card rounded-xl shadow-sm border border-border p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-amber-100 dark:bg-amber-900/30 rounded-xl shrink-0">
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-muted-foreground">Horas Extras</p>
                        <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ minutesToHours($extraMinutes) }}</p>
                    </div>
                </div>
            </div>

            {{-- Saldo --}}
            <div class="bg-card rounded-xl shadow-sm border border-border p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 {{ $balanceMinutes >= 0 ? 'bg-purple-100 dark:bg-purple-900/30' : 'bg-red-100 dark:bg-red-900/30' }} rounded-xl shrink-0">
                        <svg class="w-6 h-6 {{ $balanceMinutes >= 0 ? 'text-purple-600 dark:text-purple-400' : 'text-red-600 dark:text-red-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-muted-foreground">Saldo</p>
                        <p class="text-2xl font-bold {{ $balanceMinutes >= 0 ? 'text-purple-600 dark:text-purple-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $balanceMinutes >= 0 ? '+' : '' }}{{ minutesToHours($balanceMinutes) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabela de Espelho de Horas --}}
        <div class="bg-card rounded-xl shadow-sm border border-border overflow-hidden">
            <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                <h3 class="text-lg font-semibold text-foreground">Espelho de Horas</h3>
                <span class="px-3 py-1 text-sm font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full">
                    {{ $workedDays }} {{ $workedDays === 1 ? 'dia' : 'dias' }}
                </span>
            </div>
            
            <div>
                <table class="w-full">
                    <thead class="bg-muted/50 bg-secondary/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Entrada</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Intervalo</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Volta</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Saída</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Hora Extra</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border dark:divide-border">
                        @forelse($dailyPoints as $point)
                            <tr class="hover:bg-muted/50 hover:bg-accent/30">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-foreground">
                                    {{ $point->work_date->format('d/m/Y') }}
                                    <span class="text-muted-foreground font-normal">({{ $point->work_date->translatedFormat('D') }})</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-muted-foreground">
                                    {{ $point->entry_time ? $point->entry_time->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-muted-foreground">
                                    {{ $point->lunch_out_time ? $point->lunch_out_time->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-muted-foreground">
                                    {{ $point->lunch_return_time ? $point->lunch_return_time->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-muted-foreground">
                                    {{ $point->exit_time ? $point->exit_time->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($point->extra_start_time && $point->extra_end_time)
                                        <span class="text-amber-600 dark:text-amber-400">{{ $point->extra_start_time->format('H:i') }} - {{ $point->extra_end_time->format('H:i') }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-foreground">
                                    {{ $point->total_hours_formatted }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-muted-foreground mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-muted-foreground">Nenhuma hora registrada para este período.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($dailyPoints->count() > 0)
                        <tfoot class="bg-muted/50 bg-secondary/50">
                            <tr>
                                <td colspan="6" class="px-6 py-3 text-right text-sm font-semibold text-foreground">
                                    Total:
                                </td>
                                <td class="px-6 py-3 text-sm font-bold text-foreground">
                                    {{ minutesToHours($totalMinutes) }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        {{-- Tarefas e Pull Requests --}}
        <div class="bg-card rounded-xl shadow-sm border border-border overflow-hidden">
            <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                <h3 class="text-lg font-semibold text-foreground">Produção do Período</h3>
                <span class="px-3 py-1 text-sm font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-full">
                    {{ $tasks->count() }} {{ $tasks->count() === 1 ? 'tarefa' : 'tarefas' }}
                </span>
            </div>
            
            <div>
                <table class="w-full">
                    <thead class="bg-muted/50 bg-secondary/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Tarefa</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Pull Requests</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border dark:divide-border">
                        @forelse($tasks as $task)
                            <tr class="hover:bg-muted/50 hover:bg-accent/30">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-foreground">
                                    {{ $task->work_date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-foreground">
                                    <div class="font-medium">{{ $task->title }}</div>
                                    @if($task->description)
                                        <div class="text-muted-foreground text-xs mt-1">{{ Str::limit($task->description, 80) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-status-badge :status="$task->status->label()" :color="$task->status->color()" />
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($task->pullRequests->count() > 0)
                                        <div class="space-y-1">
                                            @foreach($task->pullRequests as $pr)
                                                <a href="{{ $pr->url }}" target="_blank" class="inline-flex items-center gap-1 text-primary hover:underline">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                    </svg>
                                                    #{{ $pr->pr_number }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-muted-foreground mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                        <p class="text-muted-foreground">Nenhuma tarefa registrada para este período.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Relatório Financeiro do Mês --}}
        <div class="bg-card rounded-xl shadow-sm border border-border overflow-hidden">
            <div class="px-6 py-4 border-b border-border">
                <h3 class="text-lg font-semibold text-foreground">Resumo Financeiro</h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-muted/50 bg-secondary/50 rounded-lg">
                        <p class="text-sm text-muted-foreground mb-1">Receita Total</p>
                        <p class="text-xl font-bold text-green-600 dark:text-green-400">{{ $financialData['formatted_total_revenue'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $financialData['invoices']->count() }} nota(s)</p>
                    </div>
                    <div class="text-center p-4 bg-muted/50 bg-secondary/50 rounded-lg">
                        <p class="text-sm text-muted-foreground mb-1">DAS Pago</p>
                        <p class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ $financialData['formatted_total_das_paid'] }}</p>
                    </div>
                    <div class="text-center p-4 bg-muted/50 bg-secondary/50 rounded-lg">
                        <p class="text-sm text-muted-foreground mb-1">DAS Pendente</p>
                        <p class="text-xl font-bold text-amber-600 dark:text-amber-400">{{ $financialData['formatted_total_das_pending'] }}</p>
                    </div>
                    <div class="text-center p-4 bg-muted/50 bg-secondary/50 rounded-lg">
                        <p class="text-sm text-muted-foreground mb-1">Saldo Financeiro</p>
                        <p class="text-xl font-bold {{ $financialData['balance'] >= 0 ? 'text-purple-600 dark:text-purple-400' : 'text-red-600 dark:text-red-400' }}">{{ $financialData['formatted_balance'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
