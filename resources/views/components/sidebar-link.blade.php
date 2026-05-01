@props(['active' => false, 'collapsed' => false])

@php
$classes = $active
    ? 'bg-primary-50 text-primary-700 sidebar-link-active'
    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 sidebar-link-hover';
@endphp

<a 
    {{ $attributes->merge(['class' => "flex items-center gap-3 px-3 py-2.5 mb-2 rounded-xl text-sm font-medium transition-colors $classes"]) }}
    :class="collapsed ? 'justify-center' : ''"
    :title="collapsed ? '{{ $slot }}' : ''"
>
    <span class="flex-shrink-0">{{ $icon }}</span>
    <span 
        x-show="!collapsed" 
        x-transition:enter="transition-opacity duration-200" 
        x-transition:leave="transition-opacity duration-100"
    >{{ $slot }}</span>
</a>
