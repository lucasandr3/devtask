@props(['align' => 'left'])

@php
$alignClass = match($align) {
    'right' => 'text-right',
    'center' => 'text-center',
    default => 'text-left',
};
@endphp

<th {{ $attributes->merge(['class' => "data-table-th $alignClass"]) }}>
    {{ $slot }}
</th>
