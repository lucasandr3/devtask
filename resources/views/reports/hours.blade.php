<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('relatorios.index') }}" class="p-2 bg-gray-100 dark:bg-slate-700 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="page-title">Relatório de Horas</h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        {{-- Filtros e Ações --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-4">
            <div class="flex flex-wrap gap-4 items-end justify-between">
                {{-- Filtro de Período --}}
                <form method="GET" action="{{ route('relatorios.horas') }}" class="flex flex-wrap gap-4 items-end">
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
                @endif
            </div>
        </div>

        {{-- Cards de Resumo --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total de Horas</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalWorked }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Dias Trabalhados</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $workedDays }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Média por Dia</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $avgWorked }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabela de Registros --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detalhamento Diário</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-slate-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Entrada</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Saída p/ Almoço</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Retorno do Almoço</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Saída</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                        @forelse($dailyPoints as $point)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $point->work_date->format('d/m/Y') }}
                                    <span class="text-gray-500 dark:text-gray-400 font-normal">({{ $point->work_date->translatedFormat('l') }})</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $point->entry_time ? $point->entry_time->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $point->lunch_out_time ? $point->lunch_out_time->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $point->lunch_return_time ? $point->lunch_return_time->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $point->exit_time ? $point->exit_time->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $point->total_hours_formatted }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400">Nenhum registro de horas encontrado para este período.</p>
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
