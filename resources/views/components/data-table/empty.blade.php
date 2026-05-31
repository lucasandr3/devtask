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
                <svg class="data-table-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            @endif
            <p class="data-table-empty-message">{{ $message }}</p>
            @if($createRoute)
                <a href="{{ $createRoute }}" class="btn-primary btn-responsive ui-tooltip ui-tooltip-top" data-tooltip="{{ $createLabel }}" aria-label="{{ $createLabel }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="btn-text">{{ $createLabel }}</span>
                </a>
            @endif
        </div>
    </td>
</tr>
