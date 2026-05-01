{{-- Mobile Header - Only visible on mobile/tablet --}}
<header x-data class="lg:hidden fixed top-0 left-0 right-0 h-16 bg-white/80 backdrop-blur-xl border-b border-gray-200/50 z-30 dark-header">
    <div class="flex items-center justify-between h-full px-4">
        {{-- Menu Button --}}
        <button 
            @click="$dispatch('toggle-mobile-sidebar')"
            class="p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        {{-- Logo --}}
        <a href="{{ route('painel') }}" class="flex items-center gap-2">
            <div class="p-1.5 bg-primary-600 rounded-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
            </div>
            <span class="text-lg font-bold text-gray-900 dark:text-white">DevTask</span>
        </a>

        {{-- Right Actions --}}
        <div class="flex items-center gap-2">
            <x-theme-selector />
            <x-dark-mode-toggle />
        </div>
    </div>
</header>
