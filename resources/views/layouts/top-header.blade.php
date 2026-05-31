{{-- Top Header - Desktop --}}
<header class="fixed top-0 left-0 right-0 z-50 h-14 border-b border-sidebar-border bg-sidebar text-sidebar-foreground hidden lg:flex">
    {{-- Sidebar brand area --}}
    <div class="sidebar-brand flex items-center gap-2.5 px-4 border-r border-sidebar-border flex-shrink-0 transition-all duration-300 overflow-hidden">
        <x-ui.logo :href="route('painel')" class="min-w-0" />
    </div>

    {{-- Main header bar --}}
    <div class="flex-1 flex items-center justify-between px-4 min-w-0">
        {{-- Left: toggle + page title --}}
        <div class="flex items-center gap-3 min-w-0">
            <button
                type="button"
                onclick="window.dispatchEvent(new CustomEvent('toggle-sidebar-collapse'))"
                class="inline-flex items-center justify-center rounded-md h-9 w-9 hover:bg-accent hover:text-accent-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring ui-tooltip ui-tooltip-bottom"
                data-tooltip="Alternar menu"
                aria-label="Alternar menu"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            @isset($header)
                <div class="min-w-0 text-base font-semibold tracking-tight truncate [&_.page-title]:text-base [&_.page-title]:font-semibold [&_.page-title]:tracking-tight [&_.page-title]:m-0">
                    {{ $header }}
                </div>
            @endisset
        </div>

        {{-- Right: page actions + global actions --}}
        <div class="flex items-center gap-1 flex-shrink-0">
            @isset($headerActions)
                <div class="flex items-center gap-2 mr-1">
                    {{ $headerActions }}
                </div>
                <div class="mx-2 h-6 w-px bg-sidebar-border"></div>
            @endisset

            <x-theme-selector icon-only />

            <div class="mx-2 h-6 w-px bg-sidebar-border"></div>

            <x-header-notifications />

            <div class="ml-2">
                <x-header-user-menu />
            </div>
        </div>
    </div>
</header>
