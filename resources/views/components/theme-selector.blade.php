@props(['iconOnly' => false])

<div x-data="{
    open: false,
    themes: [
        { id: 'blue', name: 'Azul Oceano', color: '#3b82f6' },
        { id: 'indigo', name: 'Índigo Noturno', color: '#6366f1' },
        { id: 'emerald', name: 'Esmeralda', color: '#10b981' },
        { id: 'rose', name: 'Rosa Elegante', color: '#f43f5e' },
        { id: 'amber', name: 'Âmbar Quente', color: '#f59e0b' },
        { id: 'violet', name: 'Violeta', color: '#8b5cf6' },
        { id: 'teal', name: 'Verde-azulado', color: '#14b8a6' },
        { id: 'slate', name: 'Ardósia', color: '#64748b' },
        { id: 'cyan', name: 'Ciano', color: '#06b6d4' },
        { id: 'fuchsia', name: 'Fúcsia', color: '#d946ef' },
    ],
    currentTheme: localStorage.getItem('gestorpro-theme') || 'blue',
    currentMode: window.ThemeManager?.getMode() || 'system',
    darkMode: window.ThemeManager?.isDarkMode() ?? document.documentElement.classList.contains('dark'),
    setTheme(themeId) {
        this.currentTheme = themeId;
        window.ThemeManager.setTheme(themeId);
    },
    setMode(mode) {
        this.currentMode = mode;
        window.ThemeManager.setMode(mode);
        this.darkMode = window.ThemeManager.isDarkMode();
    },
    getCurrentColor() {
        const theme = this.themes.find(t => t.id === this.currentTheme);
        return theme ? theme.color : '#3b82f6';
    },
}" 
@click.away="open = false"
@theme-changed.window="currentTheme = $event.detail.theme"
@theme-mode-changed.window="currentMode = $event.detail.mode; darkMode = window.ThemeManager.isDarkMode()"
@dark-mode-changed.window="darkMode = $event.detail.darkMode; currentMode = window.ThemeManager.getMode()"
class="relative">
    <button 
        @click="open = !open"
        type="button"
        @class([
            'inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 hover:bg-accent hover:text-accent-foreground ui-tooltip ui-tooltip-bottom',
            'h-9 w-9' => $iconOnly,
            'gap-2 h-9 px-3 border border-input bg-background' => !$iconOnly,
        ])
        x-bind:data-tooltip="darkMode ? 'Tema (modo escuro)' : 'Tema (modo claro)'"
        aria-label="Selecionar tema"
    >
        @if($iconOnly)
            <svg x-show="darkMode" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <svg x-show="!darkMode" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
            </svg>
        @else
            <span 
                class="w-3.5 h-3.5 rounded-full ring-2 ring-background shadow-sm"
                :style="'background-color: ' + getCurrentColor()"
            ></span>
            <span class="hidden sm:inline">Tema</span>
            <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        @endif
    </button>

    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-72 origin-top-right rounded-md border border-border bg-popover text-popover-foreground shadow-md z-50 p-2"
        style="display: none;"
    >
        {{-- Modo claro / escuro / automático --}}
        <div class="text-xs font-medium text-muted-foreground px-2 py-1.5">
            Modo de exibição
        </div>
        <div class="grid grid-cols-3 gap-1 mb-2">
            <button
                @click="setMode('light')"
                class="flex flex-col items-center gap-1 px-2 py-2 rounded-sm text-xs transition-colors ui-tooltip ui-tooltip-top"
                data-tooltip="Claro"
                aria-label="Claro"
                :class="currentMode === 'light' ? 'bg-accent text-accent-foreground' : 'hover:bg-accent hover:text-accent-foreground text-muted-foreground'"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Claro
            </button>
            <button
                @click="setMode('dark')"
                class="flex flex-col items-center gap-1 px-2 py-2 rounded-sm text-xs transition-colors ui-tooltip ui-tooltip-top"
                data-tooltip="Escuro"
                aria-label="Escuro"
                :class="currentMode === 'dark' ? 'bg-accent text-accent-foreground' : 'hover:bg-accent hover:text-accent-foreground text-muted-foreground'"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
                Escuro
            </button>
            <button
                @click="setMode('system')"
                class="flex flex-col items-center gap-1 px-2 py-2 rounded-sm text-xs transition-colors ui-tooltip ui-tooltip-top"
                data-tooltip="Automático"
                aria-label="Automático"
                :class="currentMode === 'system' ? 'bg-accent text-accent-foreground' : 'hover:bg-accent hover:text-accent-foreground text-muted-foreground'"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Auto
            </button>
        </div>

        <div class="h-px bg-border my-1"></div>

        <div class="text-xs font-medium text-muted-foreground px-2 py-1.5">
            Cor do tema
        </div>
        <div class="grid grid-cols-2 gap-1">
            <template x-for="theme in themes" :key="theme.id">
                <button
                    @click="setTheme(theme.id)"
                    class="flex items-center gap-2 px-2 py-1.5 text-sm rounded-sm transition-colors"
                    :class="currentTheme === theme.id 
                        ? 'bg-accent text-accent-foreground' 
                        : 'hover:bg-accent hover:text-accent-foreground'"
                >
                    <span 
                        class="w-3.5 h-3.5 rounded-full ring-1 ring-border shadow-sm flex-shrink-0"
                        :style="'background-color: ' + theme.color"
                    ></span>
                    <span class="truncate text-xs" x-text="theme.name"></span>
                    <svg 
                        x-show="currentTheme === theme.id" 
                        class="w-3.5 h-3.5 ml-auto text-primary flex-shrink-0" 
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
