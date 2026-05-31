@props([
    'colspan' => 1,
    'message' => 'Nenhum registro encontrado',
    'createRoute' => null,
    'createLabel' => 'Criar primeiro registro',
    'icon' => null,
])

<tr>
    <td colspan="{{ $colspan }}" class="data-table-empty">
        <div class="data-table-empty-content">
            @if($icon)
                {!! $icon !!}
            @else
                <x-ui.icon name="description" class="data-table-empty-icon size-12 text-muted-foreground/60" />
            @endif
            <p class="data-table-empty-message">{{ $message }}</p>
            @if($createRoute)
                <a href="{{ $createRoute }}" class="btn-primary btn-responsive ui-tooltip ui-tooltip-top" data-tooltip="{{ $createLabel }}" aria-label="{{ $createLabel }}">
                    <x-ui.icon name="plus" class="size-5" />
                    <span class="btn-text">{{ $createLabel }}</span>
                </a>
            @endif
        </div>
    </td>
</tr>
