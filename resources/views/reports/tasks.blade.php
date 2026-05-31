<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Relatório de Tarefas</h2>
    </x-slot>

    <x-ui.page-back :href="route('relatorios.index')" class="mb-6" />

    <div class="space-y-6 w-full">
        {{-- Filtros e Ações --}}
        <div class="bg-card rounded-xl shadow-sm border border-border p-4">
            <div class="flex flex-wrap gap-4 items-end justify-between">
                {{-- Filtro de Período --}}
                <form method="GET" action="{{ route('relatorios.tarefas') }}" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label for="month" class="block text-sm font-medium text-foreground mb-1">Período</label>
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
                @if($tasks->count() > 0)
                    <div class="flex flex-wrap gap-4 items-end">
                        {{-- Gerar PDF --}}
                        <a href="{{ route('relatorios.tarefas.pdf', ['month' => $month]) }}" target="_blank" class="btn-secondary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Gerar PDF
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Cards de Resumo --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            {{-- Total de Tarefas --}}
            <div class="bg-card rounded-xl shadow-sm border border-border p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl shrink-0">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-muted-foreground">Total</p>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalTasks }}</p>
                    </div>
                </div>
            </div>

            {{-- Concluídas --}}
            <div class="bg-card rounded-xl shadow-sm border border-border p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl shrink-0">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-muted-foreground">Concluídas</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $doneTasks }}</p>
                    </div>
                </div>
            </div>

            {{-- Em Andamento --}}
            <div class="bg-card rounded-xl shadow-sm border border-border p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl shrink-0">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-muted-foreground">Em Andamento</p>
                        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $doingTasks }}</p>
                    </div>
                </div>
            </div>

            {{-- A Fazer --}}
            <div class="bg-card rounded-xl shadow-sm border border-border p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-muted dark:bg-gray-700/30 rounded-xl shrink-0">
                        <svg class="w-6 h-6 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-muted-foreground">A Fazer</p>
                        <p class="text-2xl font-bold text-muted-foreground">{{ $todoTasks }}</p>
                    </div>
                </div>
            </div>

            {{-- Pull Requests --}}
            <div class="bg-card rounded-xl shadow-sm border border-border p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl shrink-0">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-muted-foreground">Pull Requests</p>
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $totalPRs }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabela de Tarefas --}}
        <div class="bg-card rounded-xl shadow-sm border border-border overflow-hidden">
            <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                <h3 class="text-lg font-semibold text-foreground">Tarefas do Período</h3>
                <span class="px-3 py-1 text-sm font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full">
                    {{ $totalTasks }} {{ $totalTasks === 1 ? 'tarefa' : 'tarefas' }}
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
                                    <span class="text-muted-foreground font-normal">({{ $task->work_date->translatedFormat('D') }})</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-foreground">
                                    <div class="font-medium">{{ $task->title }}</div>
                                    @if($task->description)
                                        <div class="text-muted-foreground text-xs mt-1">{{ Str::limit($task->description, 100) }}</div>
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
                                                    #{{ $pr->pr_number }}: {{ Str::limit($pr->title, 40) }}
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
    </div>
</x-app-layout>
