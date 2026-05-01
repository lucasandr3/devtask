@props(['title', 'subtitle' => null])

<td {{ $attributes->merge(['class' => 'data-table-td']) }}>
    <div class="data-table-title-cell">
        <div class="data-table-title-main">{{ $title }}</div>
        @if($subtitle)
            <div class="data-table-title-sub">{{ $subtitle }}</div>
        @endif
    </div>
</td>
