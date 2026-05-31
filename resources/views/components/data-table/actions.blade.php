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
                <x-ui.icon name="visibility" class="size-5" />
                <span class="sr-only">Ver</span>
            </a>
        @endif

        @if($editRoute)
            <a href="{{ $editRoute }}" class="action-btn edit ui-tooltip ui-tooltip-top" data-tooltip="Editar" aria-label="Editar">
                <x-ui.icon name="edit" class="size-5" />
                <span class="sr-only">Editar</span>
            </a>
        @endif

        @if($deleteRoute)
            <form method="POST" action="{{ $deleteRoute }}" class="inline" data-confirm="{{ $deleteConfirm }}" data-confirm-title="Excluir registro?">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn delete ui-tooltip ui-tooltip-top" data-tooltip="Excluir" aria-label="Excluir">
                    <x-ui.icon name="delete" class="size-5" />
                    <span class="sr-only">Excluir</span>
                </button>
            </form>
        @else
            <span class="action-btn-spacer" aria-hidden="true"></span>
        @endif
    </div>
</td>
