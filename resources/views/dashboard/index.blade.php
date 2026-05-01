<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col justify-between">
            <h2 class="page-title">
                Dashboard
            </h2>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $currentMonth }}</span>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Hours -->
            <div class="stat-card group">
                <div class="flex items-center">
                    <div class="stat-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total de Horas</dt>
                            <dd class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalHours }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Normal Hours -->
            <div class="stat-card group">
                <div class="flex items-center">
                    <div class="stat-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Horas Normais</dt>
                            <dd class="text-2xl font-bold text-gray-900 dark:text-white">{{ $normalHours }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Extra Hours -->
            <div class="stat-card group">
                <div class="flex items-center">
                    <div class="stat-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Horas Extras</dt>
                            <dd class="text-2xl font-bold text-gray-900 dark:text-white">{{ $extraHours }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Tasks Done -->
            <div class="stat-card group">
                <div class="flex items-center">
                    <div class="stat-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Tarefas Concluídas</dt>
                            <dd class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tasksDone }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Points Table -->
        <div class="card overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Últimas Horas Registradas
                </h3>
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
                        @forelse($recentPoints as $point)
                            <tr class="table-row">
                                <td class="table-cell whitespace-nowrap font-medium">
                                    {{ $point->work_date->format('d/m/Y') }}
                                </td>
                                <td class="table-cell whitespace-nowrap">
                                    <span class="font-semibold text-primary-600 dark:text-primary-400">{{ $point->total_hours_formatted }}</span>
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400">Nenhuma hora registrada ainda</p>
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
