<button 
    x-data="{ 
        darkMode: localStorage.getItem('devtask-dark-mode') === 'true' || 
            (localStorage.getItem('devtask-dark-mode') === null && window.matchMedia('(prefers-color-scheme: dark)').matches),
        toggle() {
            this.darkMode = !this.darkMode;
            window.ThemeManager.toggleDarkMode();
        }
    }"
    @dark-mode-changed.window="darkMode = $event.detail.darkMode"
    @click="toggle()"
    type="button"
    class="relative inline-flex items-center justify-center p-2 text-gray-500 dark:text-gray-400 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600 hover:text-gray-700 dark:hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 transition-all duration-200"
    :title="darkMode ? 'Modo claro' : 'Modo escuro'"
>
    <!-- Sun Icon (Light Mode) -->
    <svg 
        x-show="darkMode"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 rotate-90 scale-0"
        x-transition:enter-end="opacity-100 rotate-0 scale-100"
        class="w-5 h-5" 
        fill="none" 
        stroke="currentColor" 
        viewBox="0 0 24 24"
    >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
    </svg>

    <!-- Moon Icon (Dark Mode) -->
    <svg 
        x-show="!darkMode"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -rotate-90 scale-0"
        x-transition:enter-end="opacity-100 rotate-0 scale-100"
        class="w-5 h-5 text-slate-700 dark:text-slate-300" 
        fill="none" 
        stroke="currentColor" 
        viewBox="0 0 24 24"
    >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
    </svg>
</button>
