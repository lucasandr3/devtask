<div class="kanban-card card p-3 mb-2 cursor-move transition-all duration-200 hover:shadow-md hover:border-primary-300 dark:hover:border-primary-600"
     draggable="true"
     ondragstart="dragStart(event)"
     ondragend="dragEnd(event)"
     data-task-id="{{ $task->id }}">
    
    <div class="flex items-start justify-between gap-2 mb-2">
        <h4 class="font-medium text-gray-900 dark:text-white text-sm leading-tight line-clamp-2">
            {{ $task->title }}
        </h4>
        <div class="flex-shrink-0">
            <a href="{{ route('tarefas.editar', $task) }}" 
               class="text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors"
               title="Editar">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </a>
        </div>
    </div>
    
    @if($task->description)
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3 line-clamp-2">
            {{ $task->description }}
        </p>
    @endif
    
    <div class="flex items-center justify-between text-xs">
        <div class="flex items-center gap-1 text-gray-400 dark:text-gray-500">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span>{{ $task->work_date->format('d/m/Y') }}</span>
        </div>
        
        @if($task->pull_requests_count > 0)
            <div class="flex items-center gap-1 text-primary-600 dark:text-primary-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="font-medium">{{ $task->pull_requests_count }}</span>
            </div>
        @endif
    </div>
</div>
