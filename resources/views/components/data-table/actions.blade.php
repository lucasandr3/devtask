@props([
    'editRoute' => null,
    'deleteRoute' => null,
    'deleteConfirm' => 'Tem certeza que deseja excluir este registro?',
    'viewRoute' => null,
])

<td class="data-table-td data-table-td-actions">
    <div class="data-table-actions-cell">
        {{ $slot }}

        @if($viewRoute)
            <a href="{{ $viewRoute }}" class="action-btn view ui-tooltip ui-tooltip-top" data-tooltip="Ver" aria-label="Ver">
                <svg class="shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <span class="sr-only">Ver</span>
            </a>
        @endif

        @if($editRoute)
            <a href="{{ $editRoute }}" class="action-btn edit ui-tooltip ui-tooltip-top" data-tooltip="Editar" aria-label="Editar">
                <svg class="shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span class="sr-only">Editar</span>
            </a>
        @endif

        @if($deleteRoute)
            <form method="POST" action="{{ $deleteRoute }}" class="inline" data-confirm="{{ $deleteConfirm }}" data-confirm-title="Excluir registro?">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn delete ui-tooltip ui-tooltip-top" data-tooltip="Excluir" aria-label="Excluir">
                    <svg class="shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <span class="sr-only">Excluir</span>
                </button>
            </form>
        @else
            <span class="action-btn-spacer" aria-hidden="true"></span>
        @endif
    </div>
</td>
