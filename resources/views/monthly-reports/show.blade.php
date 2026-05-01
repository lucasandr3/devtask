<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('relatorios-mensais.index') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="page-title">Relatório Mensal</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $monthlyReport->reference_month->format('m/Y') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="card p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Resumo</h2>
                <dl class="space-y-3">
                    <div class="flex justify-between items-center">
                        <dt class="text-gray-600 dark:text-gray-400">Horas Contratadas:</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $monthlyReport->contract_hours_formatted }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-gray-600 dark:text-gray-400">Horas Normais:</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $monthlyReport->normal_hours_formatted }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-gray-600 dark:text-gray-400">Horas Extras:</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $monthlyReport->extra_hours_formatted }}</dd>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-200 dark:border-slate-700 pt-3 mt-3">
                        <dt class="font-semibold text-gray-900 dark:text-white">Total:</dt>
                        <dd class="font-bold text-lg text-gray-900 dark:text-white">{{ $monthlyReport->total_hours_formatted }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-gray-600 dark:text-gray-400">Saldo:</dt>
                        <dd class="font-medium {{ $monthlyReport->balance_minutes >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $monthlyReport->balance_hours_formatted }}
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="card p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Status</h2>
                <div class="space-y-3">
                    <div>
                        <x-status-badge :status="$monthlyReport->status->label()" :color="$monthlyReport->status->color()" />
                    </div>
                    @if($monthlyReport->approver_name)
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Aprovado por: <strong class="text-gray-900 dark:text-white">{{ $monthlyReport->approver_name }}</strong>
                        </p>
                    @endif
                    @if($monthlyReport->notes)
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Observações: <span class="text-gray-900 dark:text-white">{{ $monthlyReport->notes }}</span>
                        </p>
                    @endif
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-700 flex flex-col gap-3">
                    <a href="{{ route('relatorios-mensais.pdf', $monthlyReport) }}" target="_blank" class="btn-primary text-center">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Exportar PDF Completo
                    </a>
                    <a href="{{ route('relatorios-mensais.espelho-horas', $monthlyReport) }}" target="_blank" class="btn-secondary text-center">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Espelho de Horas (Email)
                    </a>
                    @if($monthlyReport->status->value === 'draft')
                        <form method="POST" action="{{ route('relatorios-mensais.enviar', $monthlyReport->id) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn-secondary w-full text-center">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Enviar para Aprovação
                            </button>
                        </form>
                    @endif
                </div>

                @if($monthlyReport->status->value === 'sent')
                    <div class="mt-6 space-y-3 pt-6 border-t border-gray-200 dark:border-slate-700">
                        <form method="POST" action="{{ route('relatorios-mensais.aprovar', $monthlyReport->id) }}" class="space-y-3">
                            @csrf
                            @method('PUT')
                            <div>
                                <x-input-label for="approver_name" value="Nome do Aprovador" />
                                <x-text-input type="text" name="approver_name" id="approver_name" required class="mt-1" placeholder="Digite o nome do aprovador" />
                            </div>
                            <button type="submit" class="btn-primary w-full">
                                Aprovar
                            </button>
                        </form>
                        <form method="POST" action="{{ route('relatorios-mensais.rejeitar', $monthlyReport->id) }}" class="space-y-3">
                            @csrf
                            @method('PUT')
                            <div>
                                <x-input-label for="notes" value="Motivo da Rejeição" />
                                <textarea name="notes" id="notes" rows="3" class="input mt-1" placeholder="Digite o motivo da rejeição"></textarea>
                            </div>
                            <button type="submit" class="btn-danger w-full">
                                Rejeitar
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        {{-- Relatório Financeiro --}}
        <div class="card p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Relatório Financeiro</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">Receitas</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between items-center">
                            <dt class="text-gray-600 dark:text-gray-400">Total de Receitas:</dt>
                            <dd class="font-medium text-green-600 dark:text-green-400">{{ $financialData['formatted_total_revenue'] }}</dd>
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $financialData['invoices']->count() }} nota(s) fiscal(is) emitida(s)
                        </div>
                    </dl>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">Despesas</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between items-center">
                            <dt class="text-gray-600 dark:text-gray-400">DAS Pago:</dt>
                            <dd class="font-medium text-red-600 dark:text-red-400">{{ $financialData['formatted_total_das_paid'] }}</dd>
                        </div>
                        @if($financialData['total_das_pending'] > 0)
                            <div class="flex justify-between items-center">
                                <dt class="text-gray-600 dark:text-gray-400">DAS Pendente:</dt>
                                <dd class="font-medium text-yellow-600 dark:text-yellow-400">{{ $financialData['formatted_total_das_pending'] }}</dd>
                            </div>
                        @endif
                        @if($financialData['total_das_overdue'] > 0)
                            <div class="flex justify-between items-center">
                                <dt class="text-gray-600 dark:text-gray-400">DAS Vencido:</dt>
                                <dd class="font-medium text-red-600 dark:text-red-400">{{ $financialData['formatted_total_das_overdue'] }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-700">
                <div class="flex justify-between items-center">
                    <dt class="text-lg font-semibold text-gray-900 dark:text-white">Saldo Financeiro:</dt>
                    <dd class="text-xl font-bold {{ $financialData['balance'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $financialData['formatted_balance'] }}
                    </dd>
                </div>
            </div>
        </div>

        <div class="card overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-slate-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Espelho de Horas</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="table-header">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        @forelse($dailyPoints as $point)
                            <tr class="table-row">
                                <td class="table-cell whitespace-nowrap font-medium text-gray-900 dark:text-white">
                                    {{ $point->work_date->format('d/m/Y') }}
                                </td>
                                <td class="table-cell whitespace-nowrap text-gray-900 dark:text-white">
                                    {{ $point->total_hours_formatted }}
                                </td>
                                <td class="table-cell whitespace-nowrap">
                                    <x-status-badge :status="$point->status->label()" :color="$point->status->color()" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400">Nenhuma hora registrada</p>
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
