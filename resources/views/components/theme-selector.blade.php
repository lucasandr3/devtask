<div x-data="{
    open: false,
    themes: [
        { id: 'blue', name: 'Ocean Blue', color: '#3b82f6' },
        { id: 'indigo', name: 'Indigo Night', color: '#6366f1' },
        { id: 'emerald', name: 'Emerald Fresh', color: '#10b981' },
        { id: 'rose', name: 'Rose Elegant', color: '#f43f5e' },
        { id: 'amber', name: 'Amber Warm', color: '#f59e0b' },
        { id: 'violet', name: 'Violet Dream', color: '#8b5cf6' },
        { id: 'teal', name: 'Teal Ocean', color: '#14b8a6' },
        { id: 'slate', name: 'Slate Pro', color: '#64748b' },
        { id: 'cyan', name: 'Cyan Tech', color: '#06b6d4' },
        { id: 'fuchsia', name: 'Fuchsia Bold', color: '#d946ef' },
    ],
    currentTheme: localStorage.getItem('devtask-theme') || 'blue',
    setTheme(themeId) {
        this.currentTheme = themeId;
        window.ThemeManager.setTheme(themeId);
        this.open = false;
    },
    getCurrentColor() {
        const theme = this.themes.find(t => t.id === this.currentTheme);
        return theme ? theme.color : '#3b82f6';
    }
}" 
@click.away="open = false"
@theme-changed.window="currentTheme = $event.detail.theme"
class="relative">
    <!-- Trigger Button -->
    <button 
        @click="open = !open"
        type="button"
        class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 transition-all duration-200"
        title="Selecionar tema"
    >
        <span 
            class="w-4 h-4 rounded-full ring-2 ring-white dark:ring-slate-700 shadow-sm"
            :style="'background-color: ' + getCurrentColor()"
        ></span>
        <!-- <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
        </svg> -->
        <span class="hidden sm:inline">Tema</span>
        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Dropdown -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 origin-top-right bg-white dark:bg-slate-800 rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 dark:ring-slate-700 focus:outline-none z-50 p-2"
        style="display: none;"
    >
        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 py-2">
            Selecione um tema
        </div>
        <div class="grid grid-cols-2 gap-1">
            <template x-for="theme in themes" :key="theme.id">
                <button
                    @click="setTheme(theme.id)"
                    class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg transition-all duration-150"
                    :class="currentTheme === theme.id 
                        ? 'bg-primary-50 dark:bg-slate-700 text-primary-700 dark:text-primary-300' 
                        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700'"
                >
                    <span 
                        class="w-4 h-4 rounded-full ring-1 ring-gray-200 dark:ring-slate-600 shadow-sm flex-shrink-0"
                        :style="'background-color: ' + theme.color"
                    ></span>
                    <span class="truncate text-xs" x-text="theme.name"></span>
                    <svg 
                        x-show="currentTheme === theme.id" 
                        class="w-4 h-4 ml-auto text-primary-600 dark:text-primary-400 flex-shrink-0" 
                        fill="currentColor" 
                        viewBox="0 0 20 20"
                    >
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </template>
        </div>
    </div>
</div>
