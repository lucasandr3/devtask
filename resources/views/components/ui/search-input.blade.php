@props([
    'placeholder' => 'Pesquisar...',
    'tableId' => null,
])

<div {{ $attributes->merge(['class' => 'search-input-wrapper']) }}>
    <x-ui.icon name="search" class="search-input-icon" />
    <input
        type="text"
        placeholder="{{ $placeholder }}"
        @if($tableId) id="{{ $tableId }}Search" data-table-id="{{ $tableId }}" @endif
        class="search-input"
    >
</div>
