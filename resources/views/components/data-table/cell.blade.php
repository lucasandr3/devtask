@props(['align' => 'left'])

@php
$alignClass = match($align) {
    'right' => 'text-right',
    'center' => 'text-center',
    default => 'text-left',
};
@endphp

<td {{ $attributes->merge(['class' => "data-table-td $alignClass"]) }}>
    {{ $slot }}
</td>
