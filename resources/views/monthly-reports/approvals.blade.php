<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Aprovar Relatórios Mensais</h2>
    </x-slot>

    <div class="card">
        <div>
            <table class="table">
                <thead class="table-header">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Colaborador</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Mês</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Horas</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody class="table-body">
                    @forelse($reports as $report)
                        <tr class="table-row">
                            <td class="table-cell font-medium">{{ $report->user->name }}</td>
                            <td class="table-cell">{{ $report->reference_month->format('m/Y') }}</td>
                            <td class="table-cell">{{ $report->total_hours_formatted }}</td>
                            <td class="table-cell">
                                <x-status-badge :status="$report->status->label()" :color="$report->status->color()" />
                            </td>
                            <td class="table-cell text-right">
                                <div class="flex items-center justify-end gap-2 flex-wrap">
                                    <a href="{{ route('relatorios-mensais.espelho-horas', $report) }}" target="_blank" class="btn-secondary text-xs px-2 py-1">PDF</a>
                                    @if($report->status->value === 'sent')
                                        <form method="POST" action="{{ route('relatorios-mensais.aprovar', $report->id) }}" class="inline-flex items-center gap-1">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="approver_name" value="{{ auth()->user()->name }}">
                                            <button type="submit" class="btn-primary text-xs px-2 py-1">Aprovar</button>
                                        </form>
                                        <form method="POST" action="{{ route('relatorios-mensais.rejeitar', $report->id) }}" class="inline-flex items-center gap-1">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="notes" value="Revisar e reenviar.">
                                            <button type="submit" class="btn-danger text-xs px-2 py-1">Rejeitar</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-muted-foreground">Nenhum relatório enviado ainda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reports->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
