@props(['status', 'color'])

@php
$colorClasses = match($color) {
    'green' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 ring-green-600/20 dark:ring-green-500/30',
    'yellow' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 ring-yellow-600/20 dark:ring-yellow-500/30',
    'red' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 ring-red-600/20 dark:ring-red-500/30',
    'blue' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 ring-blue-600/20 dark:ring-blue-500/30',
    'purple' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400 ring-purple-600/20 dark:ring-purple-500/30',
    'gray' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 ring-gray-600/20 dark:ring-gray-500/30',
    'orange' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400 ring-orange-600/20 dark:ring-orange-500/30',
    default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 ring-gray-600/20 dark:ring-gray-500/30',
};
@endphp

<span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full ring-1 ring-inset {{ $colorClasses }}">
    {{ $status }}
</span>
