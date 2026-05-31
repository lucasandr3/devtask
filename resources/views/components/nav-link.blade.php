@props(['active'])

@php
$classes = ($active ?? false)
            ? 'bg-accent text-accent-foreground'
            : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground';
@endphp

<a {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-md transition-colors '.$classes]) }}>
    {{ $slot }}
</a>
