@props(['align' => 'left', 'truncate' => false])

@php
$alignClass = match($align) {
    'right' => 'text-right',
    'center' => 'text-center',
    default => 'text-left',
};
$truncateClass = $truncate ? 'data-table-td-truncate' : '';
@endphp

<td {{ $attributes->merge(['class' => "data-table-td $alignClass $truncateClass"]) }}>
    {{ $slot }}
</td>
