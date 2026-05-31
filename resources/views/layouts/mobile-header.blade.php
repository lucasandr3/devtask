{{-- Mobile Header --}}
<header class="lg:hidden fixed top-0 left-0 right-0 z-50 h-14 border-b border-sidebar-border bg-sidebar text-sidebar-foreground">
    <div class="flex items-center justify-between h-full px-3">
        <button
            type="button"
            onclick="window.dispatchEvent(new CustomEvent('toggle-mobile-sidebar'))"
            class="inline-flex items-center justify-center rounded-md h-9 w-9 hover:bg-accent hover:text-accent-foreground transition-colors"
            aria-label="Abrir menu"
            aria-expanded="false"
        >
            <x-ui.icon name="menu" class="size-5" />
        </button>

        @isset($header)
            <div class="flex-1 px-2 min-w-0 text-sm font-semibold truncate text-center [&_.page-title]:text-sm [&_.page-title]:font-semibold [&_.page-title]:m-0">
                {{ $header }}
            </div>
        @else
            <x-ui.logo :href="route('painel')" size="sm" text-class="text-sm font-bold" />
        @endisset

        <div class="flex items-center gap-1 flex-shrink-0">
            @isset($headerActions)
                <div class="flex items-center gap-1.5 mr-1 [&_a]:text-xs [&_a]:h-8 [&_a]:px-2.5">
                    {{ $headerActions }}
                </div>
            @endisset
            <x-theme-selector icon-only />
            <x-header-notifications />
            <x-header-user-menu show-text />
        </div>
    </div>
</header>
