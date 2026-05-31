<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Painel</h2>
    </x-slot>

    <div class="space-y-8">
        <p class="text-sm text-muted-foreground -mt-4">
            {{ $company?->name ?? 'Empresa' }} · {{ $currentMonth }}
        </p>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="stat-card group">
                <div class="flex items-center">
                    <div class="stat-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-muted-foreground truncate">Projetos Ativos</dt>
                            <dd class="text-2xl font-bold tracking-tight">{{ $activeProjects }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="stat-card group">
                <div class="flex items-center">
                    <div class="stat-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-muted-foreground truncate">Horas no Ponto</dt>
                            <dd class="text-2xl font-bold tracking-tight">{{ $punchHours }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="stat-card group">
                <div class="flex items-center">
                    <div class="stat-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-muted-foreground truncate">{{ $isMember ? 'Minhas Tarefas' : 'Horas em Tarefas' }}</dt>
                            <dd class="text-2xl font-bold tracking-tight">{{ $isMember ? $tasksInProgress : $trackedHours }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="stat-card group">
                <div class="flex items-center">
                    <div class="stat-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-muted-foreground truncate">Concluídas no Mês</dt>
                            <dd class="text-2xl font-bold tracking-tight">{{ $tasksDone }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="card overflow-hidden">
            <div class="px-6 py-5 border-b border-border flex items-center justify-between">
                <h3 class="text-lg font-semibold text-foreground">Projetos Recentes</h3>
                <div class="flex items-center gap-4">
                    <a href="{{ route('projetos.index') }}" class="text-sm text-primary hover:underline">Ver todos</a>
                    @if(\App\Support\CurrentCompany::canViewCompanyReports())
                        <a href="{{ route('relatorios.horas-empresa') }}" class="text-sm text-primary hover:underline">Horas da empresa</a>
                    @endif
                </div>
            </div>
            <div>
                <table class="table">
                    <thead class="table-header">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Projeto</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Tarefas</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        @forelse($recentProjects as $project)
                            <tr class="table-row">
                                <td class="table-cell">
                                    <a href="{{ route('projetos.show', $project) }}" class="font-medium text-primary hover:underline">
                                        {{ $project->name }}
                                    </a>
                                </td>
                                <td class="table-cell">
                                    <x-status-badge :status="$project->status->label()" color="blue" />
                                </td>
                                <td class="table-cell">{{ $project->tasks_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-muted-foreground">
                                    Nenhum projeto cadastrado.
                                    @if(\App\Support\CurrentCompany::canManageProjects())
                                        <a href="{{ route('projetos.create') }}" class="link-primary">Criar primeiro projeto</a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
