@props(['active' => false])

@php
    $tooltipLabel = trim(preg_replace('/\s+/', ' ', strip_tags((string) $slot)));
@endphp

<a
    {{ $attributes->merge(['class' => 'sidebar-link flex items-center gap-3 px-3 py-2 mb-1 rounded-md text-sm transition-colors ' . ($active
        ? 'bg-primary/10 text-primary font-medium dark:bg-primary/15'
        : 'text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground')]) }}
    :class="collapsed ? 'justify-center ui-tooltip ui-tooltip-right' : ''"
    x-bind:data-tooltip="collapsed ? @js($tooltipLabel) : null"
    x-bind:aria-label="collapsed ? @js($tooltipLabel) : null"
>
    <span class="flex-shrink-0 [&_svg]:size-4 [&_.material-symbols-outlined]:text-[1.125rem] [&_.material-symbols-outlined]:leading-none">{{ $icon }}</span>
    <span
        x-show="!collapsed"
        x-transition:enter="transition-opacity duration-200"
        x-transition:leave="transition-opacity duration-100"
    >{{ $slot }}</span>
</a>
