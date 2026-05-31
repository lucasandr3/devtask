@props(['active' => false, 'href' => '#', 'title' => '', 'label' => ''])

@php
    $tooltip = $label !== '' ? $label : $title;
@endphp

<a
    href="{{ $href }}"
    {{ $attributes->class([
        'toggle-group-item inline-flex items-center gap-2 h-8 px-3 text-sm font-medium rounded-md transition-colors',
        'ui-tooltip ui-tooltip-top' => $tooltip !== '',
        'bg-background text-foreground shadow-sm' => $active,
        'text-muted-foreground hover:text-foreground' => ! $active,
    ]) }}
    @if($tooltip !== '')
        data-tooltip="{{ $tooltip }}"
        aria-label="{{ $tooltip }}"
    @endif
>
    {{ $slot }}
</a>
