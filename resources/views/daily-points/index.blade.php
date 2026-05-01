<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Espelho Mensal</h2>
    </x-slot>

    <x-data-table
        :createRoute="route('horas.criar')"
        createLabel="Registrar Hora Manual"
        searchPlaceholder="Pesquisar horas..."
        :selectable="false"
        tableId="horasTable"
    >
        {{-- Filtro de Mês --}}
        <x-slot name="actions">
            <form method="GET" action="{{ route('horas.index') }}" class="flex items-center gap-2">
                <input type="text" name="month" value="{{ $currentMonth }}" class="input" data-monthpicker placeholder="Selecione o mês">
                <button type="submit" class="btn-primary btn-responsive" title="Filtrar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span class="btn-text">Filtrar</span>
                </button>
            </form>
        </x-slot>

        <x-slot name="head">
            <x-data-table.header>Data</x-data-table.header>
            <x-data-table.header>Entrada</x-data-table.header>
            <x-data-table.header>Intervalo</x-data-table.header>
            <x-data-table.header>Volta</x-data-table.header>
            <x-data-table.header>Saída</x-data-table.header>
            <x-data-table.header>Hora Extra</x-data-table.header>
            <x-data-table.header>Total</x-data-table.header>
            @if($isCurrentMonth)
                <x-data-table.header align="right">Ações</x-data-table.header>
            @endif
        </x-slot>

        @forelse($points as $point)
            <x-data-table.row>
                <x-data-table.cell class="font-medium text-gray-900 dark:text-white">
                    {{ $point->work_date->format('d/m/Y') }}
                </x-data-table.cell>
                <x-data-table.cell>
                    {{ $point->entry_time ? $point->entry_time->format('H:i') : '-' }}
                </x-data-table.cell>
                <x-data-table.cell>
                    {{ $point->lunch_out_time ? $point->lunch_out_time->format('H:i') : '-' }}
                </x-data-table.cell>
                <x-data-table.cell>
                    {{ $point->lunch_return_time ? $point->lunch_return_time->format('H:i') : '-' }}
                </x-data-table.cell>
                <x-data-table.cell>
                    {{ $point->exit_time ? $point->exit_time->format('H:i') : '-' }}
                </x-data-table.cell>
                <x-data-table.cell>
                    @if($point->extra_start_time && $point->extra_end_time)
                        <span class="text-amber-600 dark:text-amber-400">{{ $point->extra_start_time->format('H:i') }} - {{ $point->extra_end_time->format('H:i') }}</span>
                    @else
                        -
                    @endif
                </x-data-table.cell>
                <x-data-table.cell>
                    <span class="data-table-value">{{ $point->total_hours_formatted }}</span>
                </x-data-table.cell>
                @if($isCurrentMonth)
                    <td class="data-table-td text-right">
                        <div class="data-table-actions-cell">
                            <a href="{{ route('horas.editar', $point) }}" class="action-btn edit" title="Editar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span class="btn-text">Editar</span>
                            </a>
                        </div>
                    </td>
                @endif
            </x-data-table.row>
        @empty
            <x-data-table.empty 
                :colspan="$isCurrentMonth ? 8 : 7"
                message="Nenhuma hora registrada para este mês"
                :createRoute="route('horas.registrar')"
                createLabel="Registrar primeira hora"
            >
                <x-slot name="icon">
                    <svg class="data-table-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </x-slot>
            </x-data-table.empty>
        @endforelse
    </x-data-table>
</x-app-layout>
