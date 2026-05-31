<div class="kanban-card card p-3 mb-2 cursor-move transition-all duration-200 hover:shadow-md hover:border-primary/50"
     draggable="true"
     ondragstart="dragStart(event)"
     ondragend="dragEnd(event)"
     data-task-id="{{ $task->id }}">

    <div class="flex items-start justify-between gap-2 mb-2">
        <a href="{{ route('tarefas.show', $task) }}"
           class="font-medium text-foreground text-sm leading-tight line-clamp-2 hover:text-primary transition-colors flex-1 min-w-0"
           onclick="event.stopPropagation()">
            {{ $task->title }}
        </a>
        <a href="{{ route('tarefas.show', $task) }}"
           class="text-gray-400 hover:text-primary transition-colors flex-shrink-0 ui-tooltip ui-tooltip-top"
           data-tooltip="Ver detalhes"
           aria-label="Ver detalhes"
           onclick="event.stopPropagation()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
        </a>
    </div>

    @if($task->description)
        <p class="text-xs text-muted-foreground mb-2 line-clamp-2">
            {{ $task->description }}
        </p>
    @endif

    @if($task->project && !request()->routeIs('projetos.show'))
        <p class="text-xs text-primary mb-2">{{ $task->project->name }}</p>
    @endif

    @if($task->assignee)
        <p class="text-xs text-muted-foreground mb-2">
            Responsável: {{ $task->assignee->name }}
        </p>
    @endif

    <div class="flex items-center justify-between gap-2 mb-2">
        <span class="text-xs text-muted-foreground" data-task-total-minutes data-task-id="{{ $task->id }}" data-total-suffix=" registradas">
            {{ minutesToHours($task->totalTrackedMinutes()) }} registradas
        </span>
        <div class="flex items-center gap-1">
            @include('tasks.partials.timer-button', ['task' => $task, 'activeTimerTaskId' => $activeTimerTaskId ?? null])
        </div>
    </div>

    @php
        $isTimerActive = (int) ($activeTimerTaskId ?? 0) === (int) $task->id;
    @endphp
    <p
        data-task-timer-display
        data-task-id="{{ $task->id }}"
        data-mode="elapsed"
        @class(['text-xs font-medium text-primary mb-2', 'hidden' => ! $isTimerActive])
    ></p>

    <div class="flex items-center justify-between text-xs">
        <div class="flex items-center gap-1 text-gray-400 dark:text-muted-foreground">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span>{{ $task->work_date->format('d/m/Y') }}</span>
        </div>

        @if($task->pull_requests_count > 0)
            <div class="flex items-center gap-1 text-primary">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="font-medium">{{ $task->pull_requests_count }}</span>
            </div>
        @endif
    </div>
</div>
