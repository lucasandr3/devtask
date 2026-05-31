@props(['task', 'activeTimerTaskId' => null])

@php
    $isActive = (int) ($activeTimerTaskId ?? 0) === (int) $task->id;
@endphp

<button
    type="button"
    data-task-timer-toggle
    data-task-id="{{ $task->id }}"
    data-action="{{ $isActive ? 'stop' : 'start' }}"
    data-tooltip="{{ $isActive ? 'Parar cronômetro' : 'Iniciar cronômetro' }}"
    aria-label="{{ $isActive ? 'Parar cronômetro' : 'Iniciar cronômetro' }}"
    @class([
        'p-1.5 rounded-lg transition-colors ui-tooltip ui-tooltip-top',
        'text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20' => $isActive,
        'text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20' => ! $isActive,
    ])
>
    <svg data-icon="play" @class(['w-4 h-4', 'hidden' => $isActive]) fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M8 5v14l11-7z"/>
    </svg>
    <svg data-icon="stop" @class(['w-4 h-4', 'hidden' => ! $isActive]) fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M6 6h12v12H6z"/>
    </svg>
</button>
