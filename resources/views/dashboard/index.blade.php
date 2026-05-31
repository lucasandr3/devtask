<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Painel</h2>
    </x-slot>

    <div class="space-y-8">
        {{-- Boas-vindas --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-xl font-semibold text-foreground">
                    Olá, {{ explode(' ', auth()->user()->name)[0] }}!
                </h3>
                <p class="mt-1 text-sm text-muted-foreground">
                    {{ $company?->name ?? 'Empresa' }} · {{ ucfirst($currentMonth) }}
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('horas.registrar') }}" class="inline-flex items-center gap-2 rounded-xl border border-border bg-card px-4 py-2.5 text-sm font-medium text-foreground shadow-sm transition-colors hover:bg-accent">
                    <svg class="h-4 w-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Registrar Ponto
                </a>
                <a href="{{ route('tarefas.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-medium text-primary-foreground shadow-sm transition-colors hover:bg-primary/90">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Ver Tarefas
                </a>
            </div>
        </div>

        {{-- Métricas --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="group rounded-2xl border border-border bg-card p-5 shadow-sm transition-all hover:border-primary/40 hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="rounded-xl bg-blue-100 p-2.5 dark:bg-blue-900/30">
                        <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <span class="rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-900/20 dark:text-blue-300">Projetos</span>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight">{{ $activeProjects }}</p>
                <p class="mt-1 text-sm text-muted-foreground">ativos agora</p>
            </div>

            <div class="group rounded-2xl border border-border bg-card p-5 shadow-sm transition-all hover:border-primary/40 hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="rounded-xl bg-emerald-100 p-2.5 dark:bg-emerald-900/30">
                        <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300">Ponto</span>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight">{{ $punchHours }}</p>
                <p class="mt-1 text-sm text-muted-foreground">horas no mês</p>
            </div>

            <div class="group rounded-2xl border border-border bg-card p-5 shadow-sm transition-all hover:border-primary/40 hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="rounded-xl bg-amber-100 p-2.5 dark:bg-amber-900/30">
                        <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span class="rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-900/20 dark:text-amber-300">
                        {{ $isMember ? 'Andamento' : 'Tarefas' }}
                    </span>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight">{{ $isMember ? $tasksInProgress : $trackedHours }}</p>
                <p class="mt-1 text-sm text-muted-foreground">
                    {{ $isMember ? 'tarefas em progresso' : 'horas registradas' }}
                </p>
            </div>

            <div class="group rounded-2xl border border-border bg-card p-5 shadow-sm transition-all hover:border-primary/40 hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="rounded-xl bg-violet-100 p-2.5 dark:bg-violet-900/30">
                        <svg class="h-5 w-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <span class="rounded-full bg-violet-50 px-2 py-0.5 text-xs font-medium text-violet-700 dark:bg-violet-900/20 dark:text-violet-300">Concluídas</span>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight">{{ $tasksDone }}</p>
                <p class="mt-1 text-sm text-muted-foreground">tarefas no mês</p>
            </div>
        </div>

        {{-- Resumo do dia + cronômetro --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Ponto de hoje --}}
            <div class="rounded-2xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-2">
                    <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="font-semibold text-foreground">Hoje</h3>
                </div>
                <p class="text-sm capitalize text-muted-foreground">{{ $todayFormatted }}</p>
                <div class="mt-4 flex items-end gap-3">
                    <div>
                        <p class="text-3xl font-bold tracking-tight">{{ $todayHours }}</p>
                        <p class="text-xs text-muted-foreground">horas registradas</p>
                    </div>
                    @if($nextPunchType)
                        <span class="mb-1 rounded-full bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary">
                            Próximo: {{ $nextPunchType->label() }}
                        </span>
                    @elseif($todayPoint?->exit_time)
                        <span class="mb-1 rounded-full bg-emerald-500/10 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:text-emerald-400">
                            Ponto completo
                        </span>
                    @endif
                </div>
                <a href="{{ route('horas.registrar') }}" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-muted px-4 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-accent">
                    {{ $nextPunchType ? 'Registrar ' . $nextPunchType->label() : 'Ver Ponto de Hoje' }}
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            {{-- Cronômetro ativo --}}
            <div class="rounded-2xl border border-border bg-card p-6 shadow-sm {{ $activeTimer ? 'border-amber-300 dark:border-amber-700' : '' }}">
                <div class="mb-4 flex items-center gap-2">
                    <svg class="h-5 w-5 {{ $activeTimer ? 'text-amber-500' : 'text-muted-foreground' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="font-semibold text-foreground">Cronômetro</h3>
                    @if($activeTimer)
                        <span class="ml-auto flex items-center gap-1.5 rounded-full bg-amber-500/10 px-2 py-0.5 text-xs font-medium text-amber-700 dark:text-amber-400">
                            <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-amber-500"></span>
                            Ativo
                        </span>
                    @endif
                </div>
                @if($activeTimer)
                    @php
                        $elapsedSeconds = (int) $activeTimer->started_at->diffInSeconds(now());
                    @endphp
                    <p class="truncate text-sm font-medium text-foreground">{{ $activeTimer->task->title }}</p>
                    <p class="truncate text-xs text-muted-foreground">{{ $activeTimer->task->project?->name }}</p>
                    <p
                        class="mt-3 font-mono text-3xl font-bold tracking-tight text-amber-600 dark:text-amber-400"
                        data-task-timer-display
                        data-task-id="{{ $activeTimer->task_id }}"
                        data-mode="elapsed"
                    >{{ sprintf('%02d:%02d:%02d', intdiv($elapsedSeconds, 3600), intdiv($elapsedSeconds % 3600, 60), $elapsedSeconds % 60) }}</p>
                    <a href="{{ route('tarefas.show', $activeTimer->task) }}" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-amber-600">
                        Ir para Tarefa
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @else
                    <p class="text-sm text-muted-foreground">Nenhum cronômetro em execução.</p>
                    <p class="mt-1 text-xs text-muted-foreground">Inicie o timer em uma tarefa para acompanhar aqui.</p>
                    <a href="{{ route('tarefas.index') }}" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-muted px-4 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-accent">
                        Abrir Tarefas
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endif
            </div>

            {{-- Resumo de tarefas --}}
            <div class="rounded-2xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-2">
                    <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="font-semibold text-foreground">Tarefas</h3>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div class="rounded-xl bg-muted/50 p-3 text-center">
                        <p class="text-2xl font-bold text-foreground">{{ $tasksTodo }}</p>
                        <p class="text-xs text-muted-foreground">A fazer</p>
                    </div>
                    <div class="rounded-xl bg-amber-50 p-3 text-center dark:bg-amber-900/20">
                        <p class="text-2xl font-bold text-amber-700 dark:text-amber-400">{{ $tasksInProgress }}</p>
                        <p class="text-xs text-muted-foreground">Fazendo</p>
                    </div>
                    <div class="rounded-xl bg-emerald-50 p-3 text-center dark:bg-emerald-900/20">
                        <p class="text-2xl font-bold text-emerald-700 dark:text-emerald-400">{{ $tasksDone }}</p>
                        <p class="text-xs text-muted-foreground">Feitas</p>
                    </div>
                </div>
                <div class="mt-5 flex flex-col gap-2">
                    <a href="{{ route('tarefas.index') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90">
                        Kanban de Tarefas
                    </a>
                    @if(\App\Support\CurrentCompany::canManageProjects())
                        <a href="{{ route('tarefas.create') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-border px-4 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-accent">
                            Nova Tarefa
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Listas recentes --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            {{-- Tarefas recentes --}}
            <div class="card overflow-hidden">
                <div class="flex items-center justify-between border-b border-border px-6 py-4">
                    <h3 class="text-lg font-semibold text-foreground">Tarefas Recentes</h3>
                    <a href="{{ route('tarefas.index') }}" class="text-sm text-primary hover:underline">Ver todas</a>
                </div>
                <div class="divide-y divide-border">
                    @forelse($recentTasks as $task)
                        <a href="{{ route('tarefas.show', $task) }}" class="flex items-center gap-4 px-6 py-4 transition-colors hover:bg-muted/50">
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-medium text-foreground">{{ $task->title }}</p>
                                <p class="truncate text-xs text-muted-foreground">{{ $task->project?->name ?? 'Sem projeto' }}</p>
                            </div>
                            <x-status-badge :status="$task->status->label()" :color="$task->status->color()" />
                        </a>
                    @empty
                        <div class="px-6 py-12 text-center text-muted-foreground">
                            Nenhuma tarefa ainda.
                            @if(\App\Support\CurrentCompany::canManageProjects())
                                <a href="{{ route('tarefas.create') }}" class="link-primary">Criar tarefa</a>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Projetos recentes --}}
            <div class="card overflow-hidden">
                <div class="flex items-center justify-between border-b border-border px-6 py-4">
                    <h3 class="text-lg font-semibold text-foreground">Projetos Recentes</h3>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('projetos.index') }}" class="text-sm text-primary hover:underline">Ver todos</a>
                        @if(\App\Support\CurrentCompany::canViewCompanyReports())
                            <a href="{{ route('relatorios.horas-empresa') }}" class="hidden text-sm text-primary hover:underline sm:inline">Horas da empresa</a>
                        @endif
                    </div>
                </div>
                <div class="divide-y divide-border">
                    @forelse($recentProjects as $project)
                        <a href="{{ route('projetos.show', $project) }}" class="flex items-center gap-4 px-6 py-4 transition-colors hover:bg-muted/50">
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-medium text-foreground">{{ $project->name }}</p>
                                <p class="text-xs text-muted-foreground">{{ $project->tasks_count }} {{ $project->tasks_count === 1 ? 'tarefa' : 'tarefas' }}</p>
                            </div>
                            <x-status-badge :status="$project->status->label()" color="blue" />
                        </a>
                    @empty
                        <div class="px-6 py-12 text-center text-muted-foreground">
                            Nenhum projeto cadastrado.
                            @if(\App\Support\CurrentCompany::canManageProjects())
                                <a href="{{ route('projetos.create') }}" class="link-primary">Criar primeiro projeto</a>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
