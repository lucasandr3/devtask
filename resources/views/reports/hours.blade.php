<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Relatório de Horas</h2>
    </x-slot>

    <x-ui.page-back :href="route('relatorios.index')" class="mb-6" />

    <div class="space-y-6 w-full">
        {{-- Filtros e Ações --}}
        <div class="bg-card rounded-xl shadow-sm border border-border p-4">
            <div class="flex flex-wrap gap-4 items-end justify-between">
                {{-- Filtro de Período --}}
                <form method="GET" action="{{ route('relatorios.horas') }}" class="flex flex-wrap gap-4 items-end">
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
                @if($dailyPoints->count() > 0)
                    <div class="flex flex-wrap gap-4 items-end">
                        {{-- Gerar PDF --}}
                        <a href="{{ route('relatorios.horas.pdf', ['month' => $month]) }}" target="_blank" class="btn-secondary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Gerar PDF
                        </a>

                        {{-- Enviar por Email --}}
                        <form method="POST" action="{{ route('relatorios.horas.enviar-email') }}" class="flex flex-wrap gap-2 items-end">
                            @csrf
                            <input type="hidden" name="month" value="{{ $month }}">
                            <div>
                                <label for="email" class="block text-sm font-medium text-foreground mb-1">Enviar para</label>
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
                @endif
            </div>
        </div>

        {{-- Cards de Resumo --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-card rounded-xl shadow-sm border border-border p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">Total de Horas</p>
                        <p class="text-2xl font-bold text-foreground">{{ $totalWorked }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-card rounded-xl shadow-sm border border-border p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">Dias Trabalhados</p>
                        <p class="text-2xl font-bold text-foreground">{{ $workedDays }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-card rounded-xl shadow-sm border border-border p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">Média por Dia</p>
                        <p class="text-2xl font-bold text-foreground">{{ $avgWorked }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabela de Registros --}}
        <div class="bg-card rounded-xl shadow-sm border border-border overflow-hidden">
            <div class="px-6 py-4 border-b border-border">
                <h3 class="text-lg font-semibold text-foreground">Detalhamento Diário</h3>
            </div>
            
            <div>
                <table class="w-full">
                    <thead class="bg-muted/50 bg-secondary/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Entrada</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Saída p/ Almoço</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Retorno do Almoço</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Saída</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border dark:divide-border">
                        @forelse($dailyPoints as $point)
                            <tr class="hover:bg-muted/50 hover:bg-accent/30">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-foreground">
                                    {{ $point->work_date->format('d/m/Y') }}
                                    <span class="text-muted-foreground font-normal">({{ $point->work_date->translatedFormat('l') }})</span>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-foreground">
                                    {{ $point->total_hours_formatted }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-muted-foreground mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-muted-foreground">Nenhum registro de horas encontrado para este período.</p>
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
