@php
    use App\Support\NotificationFeed;

    $notifications = NotificationFeed::items();
    $count = $notifications->count();

    $typeStyles = [
        'task' => [
            'wrap' => 'bg-primary/10 text-primary',
            'dot' => 'bg-primary',
        ],
        'approval' => [
            'wrap' => 'bg-amber-500/10 text-amber-700 dark:text-amber-400',
            'dot' => 'bg-amber-500',
        ],
        'report_rejected' => [
            'wrap' => 'bg-destructive/10 text-destructive',
            'dot' => 'bg-destructive',
        ],
    ];
@endphp

<x-dropdown align="right" width="96" contentClasses="p-0">
    <x-slot name="trigger">
        <button
            type="button"
            class="relative inline-flex items-center justify-center rounded-md h-9 w-9 hover:bg-accent hover:text-accent-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring ui-tooltip ui-tooltip-bottom"
            data-tooltip="Notificações"
            aria-label="Notificações"
        >
            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            @if($count > 0)
                <span class="absolute -top-0.5 -right-0.5 flex h-[18px] min-w-[18px] items-center justify-center rounded-full bg-destructive px-1 text-[10px] font-bold text-destructive-foreground ring-2 ring-sidebar">
                    {{ $count > 9 ? '9+' : $count }}
                </span>
            @endif
        </button>
    </x-slot>

    <x-slot name="content">
        <div class="notifications-panel">
            <div class="notifications-panel-header">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-foreground leading-none">Notificações</p>
                        <p class="text-xs text-muted-foreground mt-1">
                            @if($count > 0)
                                {{ $count }} {{ $count === 1 ? 'pendência' : 'pendências' }}
                            @else
                                Tudo em dia
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="notifications-panel-list max-h-[min(24rem,70vh)] overflow-y-auto">
                @forelse($notifications as $notification)
                    @php
                        $style = $typeStyles[$notification['type'] ?? 'task'] ?? $typeStyles['task'];
                    @endphp
                    <a href="{{ $notification['url'] }}" class="notification-item group">
                        <div class="flex items-start gap-3 min-w-0">
                            <div @class(['notification-item-icon shrink-0', $style['wrap']])>
                                <x-ui.icon :name="$notification['icon'] ?? 'tasks'" class="size-4" />
                            </div>
                            <div class="min-w-0 flex-1 pt-0.5">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="text-sm font-medium text-foreground leading-snug group-hover:text-primary transition-colors">
                                        {{ $notification['title'] }}
                                    </p>
                                    <span @class(['mt-1.5 h-2 w-2 shrink-0 rounded-full', $style['dot']]) aria-hidden="true"></span>
                                </div>
                                <p class="text-xs text-muted-foreground mt-1 line-clamp-2 leading-relaxed">
                                    {{ $notification['message'] }}
                                </p>
                                <p class="text-[11px] text-muted-foreground/80 mt-1.5">
                                    {{ $notification['at']->locale('pt_BR')->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="notifications-empty">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-muted text-muted-foreground mx-auto mb-3">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-foreground">Nenhuma pendência</p>
                        <p class="text-xs text-muted-foreground mt-1">Você está em dia com suas tarefas e aprovações.</p>
                    </div>
                @endforelse
            </div>

            @if($count > 0)
                <div class="notifications-panel-footer">
                    <p class="text-[11px] text-muted-foreground text-center">
                        Clique em uma notificação para abrir
                    </p>
                </div>
            @endif
        </div>
    </x-slot>
</x-dropdown>
