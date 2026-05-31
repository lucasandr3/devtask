@props(['status', 'color'])

@php
$colorClasses = match($color) {
    'green' => 'border-transparent bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
    'yellow' => 'border-transparent bg-amber-500/10 text-amber-700 dark:text-amber-400',
    'red' => 'border-transparent bg-destructive/10 text-destructive',
    'blue' => 'border-transparent bg-primary/10 text-primary',
    'purple' => 'border-transparent bg-violet-500/10 text-violet-700 dark:text-violet-400',
    'gray' => 'border-transparent bg-secondary text-secondary-foreground',
    'orange' => 'border-transparent bg-orange-500/10 text-orange-700 dark:text-orange-400',
    default => 'border-transparent bg-secondary text-secondary-foreground',
};
@endphp

<span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors {{ $colorClasses }}">
    {{ $status }}
</span>
