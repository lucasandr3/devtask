@props(['active'])

@php
$classes = ($active ?? false)
            ? 'bg-accent text-accent-foreground border-l-2 border-primary'
            : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground border-l-2 border-transparent';
@endphp

<a {{ $attributes->merge(['class' => 'block w-full ps-3 pe-4 py-2 text-start text-sm font-medium transition-colors '.$classes]) }}>
    {{ $slot }}
</a>
