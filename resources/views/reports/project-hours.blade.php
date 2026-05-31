<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="page-title">Relatório de Horas</h2>
                <p class="text-sm text-muted-foreground">{{ $monthLabel }}</p>
            </div>
            <form method="GET" action="{{ route('relatorios.horas-empresa') }}" class="flex items-center gap-2">
                <input type="text" name="month" value="{{ $month }}" class="select-input w-40 sm:w-44" data-monthpicker placeholder="Selecione o mês" onchange="this.form.submit()">
            </form>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="card overflow-hidden">
                <div class="px-6 py-4 border-b border-border">
                    <h3 class="font-semibold text-foreground">Horas por Projeto</h3>
                </div>
                <div>
                    <table class="table">
                        <thead class="table-header">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Projeto</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase">Horas</th>
                            </tr>
                        </thead>
                        <tbody class="table-body">
                            @forelse($projectHours as $row)
                                <tr class="table-row">
                                    <td class="table-cell">{{ $row->name }}</td>
                                    <td class="table-cell text-right font-medium">{{ minutesToHours($row->total_minutes) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-8 text-center text-muted-foreground">Nenhuma hora registrada neste mês.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card overflow-hidden">
                <div class="px-6 py-4 border-b border-border">
                    <h3 class="font-semibold text-foreground">Horas por Membro</h3>
                </div>
                <div>
                    <table class="table">
                        <thead class="table-header">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Membro</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase">Horas</th>
                            </tr>
                        </thead>
                        <tbody class="table-body">
                            @forelse($memberHours as $row)
                                <tr class="table-row">
                                    <td class="table-cell">{{ $row->name }}</td>
                                    <td class="table-cell text-right font-medium">{{ minutesToHours($row->total_minutes) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-8 text-center text-muted-foreground">Nenhuma hora registrada neste mês.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-border">
                <h3 class="font-semibold text-foreground">Registros recentes</h3>
            </div>
            <div>
                <table class="table">
                    <thead class="table-header">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Membro</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Projeto</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Tarefa</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase">Duração</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        @forelse($recentEntries as $entry)
                            <tr class="table-row">
                                <td class="table-cell">{{ $entry->started_at->format('d/m/Y H:i') }}</td>
                                <td class="table-cell">{{ $entry->user->name }}</td>
                                <td class="table-cell">{{ $entry->task->project?->name ?? '-' }}</td>
                                <td class="table-cell">{{ $entry->task->title }}</td>
                                <td class="table-cell text-right">{{ minutesToHours($entry->duration_minutes ?? 0) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-muted-foreground">Nenhum registro neste mês.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
