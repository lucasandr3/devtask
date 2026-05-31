@props(['title', 'subtitle' => null])

<td {{ $attributes->merge(['class' => 'data-table-td data-table-td-truncate']) }}>
    <div class="data-table-title-cell">
        <div class="data-table-title-main ui-tooltip ui-tooltip-top" data-tooltip="{{ $title }}">{{ $title }}</div>
        @if($subtitle)
            <div class="data-table-title-sub ui-tooltip ui-tooltip-top" data-tooltip="{{ $subtitle }}">{{ $subtitle }}</div>
        @endif
    </div>
</td>
