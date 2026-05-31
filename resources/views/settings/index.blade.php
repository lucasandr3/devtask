<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Configurações</h2>
    </x-slot>

    <div class="space-y-6">
        {{-- Personalização --}}
        <div class="card p-6" x-data="{
            themes: [
                { id: 'blue', name: 'Azul Oceano', color: '#3b82f6' },
                { id: 'violet', name: 'Violeta', color: '#8b5cf6' },
                { id: 'fuchsia', name: 'Fúcsia', color: '#d946ef' },
                { id: 'rose', name: 'Rosa Elegante', color: '#f43f5e' },
                { id: 'amber', name: 'Âmbar Quente', color: '#f59e0b' },
                { id: 'yellow', name: 'Amarelo', color: '#eab308' },
                { id: 'emerald', name: 'Esmeralda', color: '#10b981' },
                { id: 'cyan', name: 'Ciano', color: '#06b6d4' },
                { id: 'indigo', name: 'Índigo Noturno', color: '#6366f1' },
                { id: 'slate', name: 'Ardósia', color: '#64748b' },
                { id: 'teal', name: 'Verde-azulado', color: '#14b8a6' },
            ],
            currentTheme: window.ThemeManager?.getCurrentTheme() || 'blue',
            currentMode: window.ThemeManager?.getMode() || 'system',
            setTheme(themeId) {
                this.currentTheme = themeId;
                if (window.ThemeManager) {
                    window.ThemeManager.setTheme(themeId);
                }
            },
            setMode(mode) {
                this.currentMode = mode;
                if (window.ThemeManager) {
                    window.ThemeManager.setMode(mode);
                }
            }
        }"
        @theme-changed.window="currentTheme = $event.detail.theme"
        @theme-mode-changed.window="currentMode = $event.detail.mode">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-primary/10 rounded-lg">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-foreground">Personalização</h3>
            </div>

            {{-- Cor do Tema --}}
            <div class="mb-8">
                <label class="block text-sm font-medium text-foreground mb-2">Cor do Tema:</label>
                <p class="text-sm text-muted-foreground mb-4">Escolha a cor principal do tema da extensão</p>
                
                <div class="grid grid-cols-6 sm:grid-cols-11 gap-3">
                    <template x-for="theme in themes" :key="theme.id">
                        <button
                            @click="setTheme(theme.id)"
                            class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-xl transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus-visible:ring-ring focus-visible:ring-offset-background ui-tooltip ui-tooltip-top"
                            :style="'background-color: ' + theme.color"
                            x-bind:data-tooltip="theme.name"
                            x-bind:aria-label="theme.name"
                            :class="currentTheme === theme.id ? 'ring-2 ring-offset-2 ring-background ring-offset-background shadow-lg scale-105' : 'hover:shadow-md'"
                        >
                            <span 
                                x-show="currentTheme === theme.id"
                                class="absolute inset-0 flex items-center justify-center"
                            >
                                <svg class="w-6 h-6 text-white drop-shadow-md" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Modo de Tema --}}
            <div>
                <label class="block text-sm font-medium text-foreground mb-4">Modo de Tema:</label>
                
                <div class="grid grid-cols-3 gap-4">
                    {{-- Claro --}}
                    <button
                        @click="setMode('light')"
                        class="relative flex flex-col items-center justify-center p-4 sm:p-6 rounded-xl border-2 transition-all duration-200"
                        :class="currentMode === 'light' 
                            ? 'border-primary bg-primary/10' 
                            : 'border-border border-border bg-muted/50 bg-card hover:border-border hover:border-border'"
                    >
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 mb-2" :class="currentMode === 'light' ? 'text-primary' : 'text-muted-foreground'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span class="text-sm font-medium" :class="currentMode === 'light' ? 'text-primary' : 'text-foreground'">Claro</span>
                    </button>

                    {{-- Escuro --}}
                    <button
                        @click="setMode('dark')"
                        class="relative flex flex-col items-center justify-center p-4 sm:p-6 rounded-xl border-2 transition-all duration-200"
                        :class="currentMode === 'dark' 
                            ? 'border-primary bg-primary/10' 
                            : 'border-border border-border bg-muted/50 bg-card hover:border-border hover:border-border'"
                    >
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 mb-2" :class="currentMode === 'dark' ? 'text-primary' : 'text-muted-foreground'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                        <span class="text-sm font-medium" :class="currentMode === 'dark' ? 'text-primary' : 'text-foreground'">Escuro</span>
                    </button>

                    {{-- Automático --}}
                    <button
                        @click="setMode('system')"
                        class="relative flex flex-col items-center justify-center p-4 sm:p-6 rounded-xl border-2 transition-all duration-200"
                        :class="currentMode === 'system' 
                            ? 'border-primary bg-primary/10' 
                            : 'border-border border-border bg-muted/50 bg-card hover:border-border hover:border-border'"
                    >
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 mb-2" :class="currentMode === 'system' ? 'text-primary' : 'text-muted-foreground'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm font-medium" :class="currentMode === 'system' ? 'text-primary' : 'text-foreground'">Automático</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Sobre o Sistema --}}
        <div class="card p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-primary/10 rounded-lg">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-foreground">Sobre o Sistema</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div class="flex justify-between py-2 border-b border-gray-100 border-border">
                    <span class="text-muted-foreground">Versão</span>
                    <span class="font-medium text-foreground">1.0.0</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100 border-border">
                    <span class="text-muted-foreground">Laravel</span>
                    <span class="font-medium text-foreground">{{ app()->version() }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100 border-border">
                    <span class="text-muted-foreground">PHP</span>
                    <span class="font-medium text-foreground">{{ phpversion() }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100 border-border">
                    <span class="text-muted-foreground">Ambiente</span>
                    <span class="font-medium text-foreground">{{ app()->environment() }}</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
