<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Relatórios</h2>
    </x-slot>

    <div class="w-full">
        {{-- Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            {{-- Card: Relatório de Horas --}}
            <div class="group bg-card rounded-2xl shadow-sm border border-border overflow-hidden hover:shadow-lg hover:border-primary/50 transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50 transition-colors">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="px-3 py-1 text-xs font-medium bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded-full">
                            Horas
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-foreground mb-2">
                        Relatório de Horas
                    </h3>
                    <p class="text-muted-foreground text-sm mb-4">
                        Visualize o registro de horas trabalhadas, médias diárias e totais por período.
                    </p>

                    {{-- Stats --}}
                    <div class="flex items-center gap-4 mb-6 py-3 px-4 bg-muted/50 bg-secondary/50 rounded-xl">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $currentMonthHoursFormatted }}</p>
                            <p class="text-xs text-muted-foreground">Este mês</p>
                        </div>
                    </div>

                    <a href="{{ route('relatorios.horas') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        Ver Relatório
                    </a>
                </div>
            </div>

            {{-- Card: Relatório Financeiro --}}
            <div class="group bg-card rounded-2xl shadow-sm border border-border overflow-hidden hover:shadow-lg hover:border-primary/50 transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl group-hover:bg-green-200 dark:group-hover:bg-green-900/50 transition-colors">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="px-3 py-1 text-xs font-medium bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 rounded-full">
                            Financeiro
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-foreground mb-2">
                        Relatório Financeiro
                    </h3>
                    <p class="text-muted-foreground text-sm mb-4">
                        Acompanhe receitas, despesas com DAS, notas fiscais e saldo mensal.
                    </p>

                    {{-- Stats --}}
                    <div class="flex items-center gap-4 mb-6 py-3 px-4 bg-muted/50 bg-secondary/50 rounded-xl">
                        <div class="text-center flex-1">
                            <p class="text-xl font-bold text-green-600 dark:text-green-400">{{ $totalRevenueFormatted }}</p>
                            <p class="text-xs text-muted-foreground">Receita do mês</p>
                        </div>
                        <div class="w-px h-10 bg-gray-200 dark:bg-slate-600"></div>
                        <div class="text-center">
                            <p class="text-xl font-bold text-foreground">{{ $invoicesCount }}</p>
                            <p class="text-xs text-muted-foreground">Notas</p>
                        </div>
                    </div>

                    <a href="{{ route('relatorios.financeiro') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        Ver Relatório
                    </a>
                </div>
            </div>

            {{-- Card: Relatórios Mensais --}}
            <div class="group bg-card rounded-2xl shadow-sm border border-border overflow-hidden hover:shadow-lg hover:border-primary/50 transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl group-hover:bg-purple-200 dark:group-hover:bg-purple-900/50 transition-colors">
                            <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <span class="px-3 py-1 text-xs font-medium bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-300 rounded-full">
                            Mensal
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-foreground mb-2">
                        Relatórios Mensais
                    </h3>
                    <p class="text-muted-foreground text-sm mb-4">
                        Gere e acompanhe relatórios mensais completos com horas, tarefas e PRs.
                    </p>

                    {{-- Stats --}}
                    <div class="flex items-center gap-4 mb-6 py-3 px-4 bg-muted/50 bg-secondary/50 rounded-xl">
                        <div class="text-center flex-1">
                            <p class="text-xl font-bold text-purple-600 dark:text-purple-400">{{ $monthlyReportsCount }}</p>
                            <p class="text-xs text-muted-foreground">Relatórios</p>
                        </div>
                        @if($pendingReportsCount > 0)
                        <div class="w-px h-10 bg-gray-200 dark:bg-slate-600"></div>
                        <div class="text-center">
                            <p class="text-xl font-bold text-amber-600 dark:text-amber-400">{{ $pendingReportsCount }}</p>
                            <p class="text-xs text-muted-foreground">Rascunhos</p>
                        </div>
                        @endif
                    </div>

                    <a href="{{ route('relatorios-mensais.index') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        Ver Relatórios
                    </a>
                </div>
            </div>

            {{-- Card: Relatório de Tarefas --}}
            <div class="group bg-card rounded-2xl shadow-sm border border-border overflow-hidden hover:shadow-lg hover:border-primary/50 transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl group-hover:bg-indigo-200 dark:group-hover:bg-indigo-900/50 transition-colors">
                            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <span class="px-3 py-1 text-xs font-medium bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300 rounded-full">
                            Produção
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-foreground mb-2">
                        Relatório de Tarefas
                    </h3>
                    <p class="text-muted-foreground text-sm mb-4">
                        Visualize tarefas e pull requests registrados por período.
                    </p>

                    {{-- Stats --}}
                    <div class="flex items-center gap-4 mb-6 py-3 px-4 bg-muted/50 bg-secondary/50 rounded-xl">
                        <div class="text-center flex-1">
                            <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400">{{ $tasksCount }}</p>
                            <p class="text-xs text-muted-foreground">Tarefas</p>
                        </div>
                        <div class="w-px h-10 bg-gray-200 dark:bg-slate-600"></div>
                        <div class="text-center flex-1">
                            <p class="text-xl font-bold text-purple-600 dark:text-purple-400">{{ $prsCount }}</p>
                            <p class="text-xs text-muted-foreground">PRs</p>
                        </div>
                    </div>

                    <a href="{{ route('relatorios.tarefas') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        Ver Relatório
                    </a>
                </div>
            </div>

            {{-- Card: Ações Rápidas --}}
            <div class="group bg-card rounded-2xl shadow-sm border border-border overflow-hidden hover:shadow-lg hover:border-primary/50 transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 bg-amber-100 dark:bg-amber-900/30 rounded-xl group-hover:bg-amber-200 dark:group-hover:bg-amber-900/50 transition-colors">
                            <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <span class="px-3 py-1 text-xs font-medium bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300 rounded-full">
                            Atalhos
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-foreground mb-2">
                        Ações Rápidas
                    </h3>
                    <p class="text-muted-foreground text-sm mb-4">
                        Acesse rapidamente as funções mais utilizadas do sistema.
                    </p>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col gap-3">
                        <form method="POST" action="{{ route('relatorios-mensais.gerar') }}" class="w-full">
                            @csrf
                            <input type="hidden" name="month" value="{{ date('Y-m') }}">
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Gerar Relatório do Mês
                            </button>
                        </form>
                        
                        <a href="{{ route('horas.index') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-muted hover:bg-gray-200 hover:bg-accent text-foreground font-semibold rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Ver Espelho Mensal
                        </a>

                        <a href="{{ route('notas-fiscais.index') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-muted hover:bg-gray-200 hover:bg-accent text-foreground font-semibold rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Ver Notas Fiscais
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
