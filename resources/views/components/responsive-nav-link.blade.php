@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center w-full px-4 py-3 text-base font-medium rounded-lg bg-primary-50 dark:bg-slate-700 text-primary-700 dark:text-primary-300 transition-all duration-200'
            : 'flex items-center w-full px-4 py-3 text-base font-medium rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-900 dark:hover:text-white transition-all duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
