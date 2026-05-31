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
            currentTheme: localStorage.getItem('gestorpro-theme') || 'blue',
            currentMode: localStorage.getItem('gestorpro-mode') || 'system',
            setTheme(themeId) {
                this.currentTheme = themeId;
                if (window.ThemeManager) {
                    window.ThemeManager.setTheme(themeId);
                }
                localStorage.setItem('gestorpro-theme', themeId);
            },
            setMode(mode) {
                this.currentMode = mode;
                localStorage.setItem('gestorpro-mode', mode);
                
                if (mode === 'light') {
                    document.documentElement.classList.remove('dark');
                } else if (mode === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    // Automático - segue preferência do sistema
                    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            }
        }">
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

        {{-- Contas de Email --}}
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-primary/10 rounded-lg">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-foreground">Contas de Email</h3>
                        <p class="text-sm text-muted-foreground">Configure suas contas de email para enviar e receber mensagens</p>
                    </div>
                </div>
                <a href="{{ route('configuracoes.emails.index') }}" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Gerenciar Contas
                </a>
            </div>
        </div>

        {{-- Configurações de Notificações --}}
        <div class="card p-6" x-data="{
            emailNotifications: localStorage.getItem('email-notifications') === 'true',
            weeklyReport: localStorage.getItem('weekly-report') === 'true',
            monthlySummary: localStorage.getItem('monthly-summary') === 'true',
            notificationEmail: localStorage.getItem('notification-email') || '',
            showHelpModal: false,
            activeTab: 'gmail',
            saveSettings() {
                localStorage.setItem('email-notifications', this.emailNotifications);
                localStorage.setItem('weekly-report', this.weeklyReport);
                localStorage.setItem('monthly-summary', this.monthlySummary);
                localStorage.setItem('notification-email', this.notificationEmail);
                
                // Mostrar notificação de sucesso
                this.$dispatch('notify', { message: 'Configurações de notificação salvas!', type: 'success' });
            }
        }">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-primary/10 rounded-lg">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-foreground">Notificações</h3>
                </div>
                
                {{-- Botão de Ajuda --}}
                <button 
                    @click="showHelpModal = true"
                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-primary bg-primary/10 bg-primary/10 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="hidden sm:inline">Como configurar</span>
                </button>
            </div>

            {{-- Modal de Ajuda para Configuração de Email --}}
            <div 
                x-show="showHelpModal" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 overflow-y-auto"
                style="display: none;"
            >
                {{-- Backdrop --}}
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showHelpModal = false"></div>
                
                {{-- Modal --}}
                <div class="flex min-h-full items-center justify-center p-4">
                    <div 
                        x-show="showHelpModal"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="relative w-full max-w-2xl bg-white bg-card rounded-2xl shadow-2xl"
                        @click.away="showHelpModal = false"
                    >
                        {{-- Header --}}
                        <div class="flex items-center justify-between p-6 border-b border-border border-border">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-primary/10 rounded-lg">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-foreground">Como Configurar Email</h3>
                            </div>
                            <button 
                                @click="showHelpModal = false"
                                class="p-2 text-gray-400 hover:text-muted-foreground dark:hover:text-gray-300 rounded-lg hover:bg-muted dark:hover:bg-gray-700 transition-colors"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        {{-- Tabs --}}
                        <div class="border-b border-border border-border">
                            <nav class="flex -mb-px overflow-x-auto">
                                <button 
                                    @click="activeTab = 'gmail'"
                                    :class="activeTab === 'gmail' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground text-muted-foreground dark:hover:text-gray-300 hover:border-border'"
                                    class="flex items-center gap-2 px-6 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap"
                                >
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 19.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/>
                                    </svg>
                                    Gmail
                                </button>
                                <button 
                                    @click="activeTab = 'outlook'"
                                    :class="activeTab === 'outlook' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground text-muted-foreground dark:hover:text-gray-300 hover:border-border'"
                                    class="flex items-center gap-2 px-6 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap"
                                >
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M24 7.387v10.478c0 .23-.08.424-.238.576-.158.154-.352.23-.58.23h-8.547v-6.959l1.6 1.229c.102.086.234.13.397.13.176 0 .324-.054.445-.163l.604-.545c.085-.086.138-.18.158-.28.026-.1.017-.2-.026-.297-.043-.1-.116-.18-.218-.244l-3.787-2.89v-.012l-.63-.477c-.104-.092-.228-.137-.37-.137-.146 0-.274.055-.384.163l-.633.478v.011l-3.787 2.891c-.103.065-.175.145-.218.244-.043.1-.052.198-.026.298.026.1.078.193.159.28l.603.544c.122.11.27.164.445.164.163 0 .295-.045.397-.131l1.6-1.228v6.958H.818c-.228 0-.422-.076-.58-.23C.08 18.29 0 18.096 0 17.866V7.387c0-.154.044-.299.133-.437.089-.137.2-.24.333-.308L11.35.168c.183-.112.387-.168.611-.168.219 0 .42.056.604.168l10.893 6.474c.133.069.244.17.333.308.089.138.133.283.133.437H24z"/>
                                    </svg>
                                    Outlook
                                </button>
                                <button 
                                    @click="activeTab = 'yahoo'"
                                    :class="activeTab === 'yahoo' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground text-muted-foreground dark:hover:text-gray-300 hover:border-border'"
                                    class="flex items-center gap-2 px-6 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap"
                                >
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M11.996 0C5.381 0 0 5.381 0 11.996 0 18.62 5.381 24 11.996 24c6.623 0 12.004-5.38 12.004-12.004C24 5.381 18.619 0 11.996 0zm4.476 7.554l-2.528 5.9 2.702 5.98h-2.482l-2.445-5.726-2.453 5.726H6.786l2.695-5.98-2.528-5.9h2.482l2.204 5.152 2.351-5.152z"/>
                                    </svg>
                                    Yahoo
                                </button>
                                <button 
                                    @click="activeTab = 'icloud'"
                                    :class="activeTab === 'icloud' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground text-muted-foreground dark:hover:text-gray-300 hover:border-border'"
                                    class="flex items-center gap-2 px-6 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap"
                                >
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M13.762 3.75c1.658 0 3.217.652 4.387 1.835a6.22 6.22 0 0 1 1.888 4.475c.002.078-.003.156-.008.233a5.471 5.471 0 0 1 3.721 5.194c0 3.024-2.462 5.488-5.488 5.488H6.238A6.238 6.238 0 0 1 0 14.737c0-2.598 1.614-4.933 4.03-5.856a6.223 6.223 0 0 1 5.862-8.131h3.87z"/>
                                    </svg>
                                    iCloud
                                </button>
                            </nav>
                        </div>

                        {{-- Content --}}
                        <div class="p-6 max-h-96 overflow-y-auto">
                            {{-- Gmail --}}
                            <div x-show="activeTab === 'gmail'" class="space-y-4">
                                <div class="flex items-start gap-3 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 19.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/>
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-red-800 dark:text-red-300">Gmail (Google)</h4>
                                        <p class="text-sm text-red-700 dark:text-red-400 mt-1">Para usar Gmail, você precisa criar uma "Senha de App"</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-3">
                                    <h5 class="font-medium text-foreground">Passo a passo:</h5>
                                    <ol class="list-decimal list-inside space-y-2 text-sm text-muted-foreground">
                                        <li>Acesse sua conta Google em <a href="https://myaccount.google.com" target="_blank" class="text-primary hover:underline">myaccount.google.com</a></li>
                                        <li>Vá em <strong>Segurança</strong> no menu lateral</li>
                                        <li>Em "Como fazer login no Google", clique em <strong>Verificação em duas etapas</strong> (ative se necessário)</li>
                                        <li>Role até <strong>Senhas de app</strong> e clique</li>
                                        <li>Selecione "Outro (nome personalizado)" e digite "GestorPro"</li>
                                        <li>Clique em <strong>Gerar</strong> e copie a senha de 16 caracteres</li>
                                        <li>Use essa senha no lugar da sua senha normal</li>
                                    </ol>
                                </div>

                                <div class="p-4 bg-muted/50 dark:bg-gray-700/50 rounded-lg">
                                    <h5 class="font-medium text-foreground mb-2">Configurações SMTP:</h5>
                                    <div class="grid grid-cols-2 gap-2 text-sm">
                                        <span class="text-muted-foreground">Servidor:</span>
                                        <code class="text-foreground font-mono">smtp.gmail.com</code>
                                        <span class="text-muted-foreground">Porta:</span>
                                        <code class="text-foreground font-mono">587 (TLS) ou 465 (SSL)</code>
                                        <span class="text-muted-foreground">Usuário:</span>
                                        <code class="text-foreground font-mono">seu-email@gmail.com</code>
                                    </div>
                                </div>
                            </div>

                            {{-- Outlook --}}
                            <div x-show="activeTab === 'outlook'" class="space-y-4">
                                <div class="flex items-start gap-3 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                    <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M24 7.387v10.478c0 .23-.08.424-.238.576-.158.154-.352.23-.58.23h-8.547v-6.959l1.6 1.229c.102.086.234.13.397.13.176 0 .324-.054.445-.163l.604-.545c.085-.086.138-.18.158-.28.026-.1.017-.2-.026-.297-.043-.1-.116-.18-.218-.244l-3.787-2.89v-.012l-.63-.477c-.104-.092-.228-.137-.37-.137-.146 0-.274.055-.384.163l-.633.478v.011l-3.787 2.891c-.103.065-.175.145-.218.244-.043.1-.052.198-.026.298.026.1.078.193.159.28l.603.544c.122.11.27.164.445.164.163 0 .295-.045.397-.131l1.6-1.228v6.958H.818c-.228 0-.422-.076-.58-.23C.08 18.29 0 18.096 0 17.866V7.387c0-.154.044-.299.133-.437.089-.137.2-.24.333-.308L11.35.168c.183-.112.387-.168.611-.168.219 0 .42.056.604.168l10.893 6.474c.133.069.244.17.333.308.089.138.133.283.133.437H24z"/>
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-blue-800 dark:text-blue-300">Outlook / Hotmail / Live</h4>
                                        <p class="text-sm text-blue-700 dark:text-blue-400 mt-1">Microsoft permite conexão SMTP diretamente</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-3">
                                    <h5 class="font-medium text-foreground">Passo a passo:</h5>
                                    <ol class="list-decimal list-inside space-y-2 text-sm text-muted-foreground">
                                        <li>Acesse <a href="https://account.microsoft.com/security" target="_blank" class="text-primary hover:underline">account.microsoft.com/security</a></li>
                                        <li>Ative a <strong>Verificação em duas etapas</strong> se ainda não estiver ativa</li>
                                        <li>Vá em <strong>Opções de segurança avançadas</strong></li>
                                        <li>Role até "Senhas de aplicativo" e crie uma nova</li>
                                        <li>Use a senha gerada no lugar da sua senha normal</li>
                                    </ol>
                                </div>

                                <div class="p-4 bg-muted/50 dark:bg-gray-700/50 rounded-lg">
                                    <h5 class="font-medium text-foreground mb-2">Configurações SMTP:</h5>
                                    <div class="grid grid-cols-2 gap-2 text-sm">
                                        <span class="text-muted-foreground">Servidor:</span>
                                        <code class="text-foreground font-mono">smtp-mail.outlook.com</code>
                                        <span class="text-muted-foreground">Porta:</span>
                                        <code class="text-foreground font-mono">587 (STARTTLS)</code>
                                        <span class="text-muted-foreground">Usuário:</span>
                                        <code class="text-foreground font-mono">seu-email@outlook.com</code>
                                    </div>
                                </div>
                            </div>

                            {{-- Yahoo --}}
                            <div x-show="activeTab === 'yahoo'" class="space-y-4">
                                <div class="flex items-start gap-3 p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                                    <svg class="w-5 h-5 text-purple-500 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M11.996 0C5.381 0 0 5.381 0 11.996 0 18.62 5.381 24 11.996 24c6.623 0 12.004-5.38 12.004-12.004C24 5.381 18.619 0 11.996 0zm4.476 7.554l-2.528 5.9 2.702 5.98h-2.482l-2.445-5.726-2.453 5.726H6.786l2.695-5.98-2.528-5.9h2.482l2.204 5.152 2.351-5.152z"/>
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-purple-800 dark:text-purple-300">Yahoo Mail</h4>
                                        <p class="text-sm text-purple-700 dark:text-purple-400 mt-1">Yahoo requer senha de app para acesso SMTP</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-3">
                                    <h5 class="font-medium text-foreground">Passo a passo:</h5>
                                    <ol class="list-decimal list-inside space-y-2 text-sm text-muted-foreground">
                                        <li>Acesse <a href="https://login.yahoo.com/account/security" target="_blank" class="text-primary hover:underline">login.yahoo.com/account/security</a></li>
                                        <li>Clique em <strong>Gerar senha de app</strong></li>
                                        <li>Selecione "Outro aplicativo" no menu</li>
                                        <li>Digite "GestorPro" como nome</li>
                                        <li>Copie a senha gerada e use no sistema</li>
                                    </ol>
                                </div>

                                <div class="p-4 bg-muted/50 dark:bg-gray-700/50 rounded-lg">
                                    <h5 class="font-medium text-foreground mb-2">Configurações SMTP:</h5>
                                    <div class="grid grid-cols-2 gap-2 text-sm">
                                        <span class="text-muted-foreground">Servidor:</span>
                                        <code class="text-foreground font-mono">smtp.mail.yahoo.com</code>
                                        <span class="text-muted-foreground">Porta:</span>
                                        <code class="text-foreground font-mono">587 (TLS) ou 465 (SSL)</code>
                                        <span class="text-muted-foreground">Usuário:</span>
                                        <code class="text-foreground font-mono">seu-email@yahoo.com</code>
                                    </div>
                                </div>
                            </div>

                            {{-- iCloud --}}
                            <div x-show="activeTab === 'icloud'" class="space-y-4">
                                <div class="flex items-start gap-3 p-4 bg-muted dark:bg-gray-700/50 rounded-lg border border-border dark:border-gray-600">
                                    <svg class="w-5 h-5 text-muted-foreground mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M13.762 3.75c1.658 0 3.217.652 4.387 1.835a6.22 6.22 0 0 1 1.888 4.475c.002.078-.003.156-.008.233a5.471 5.471 0 0 1 3.721 5.194c0 3.024-2.462 5.488-5.488 5.488H6.238A6.238 6.238 0 0 1 0 14.737c0-2.598 1.614-4.933 4.03-5.856a6.223 6.223 0 0 1 5.862-8.131h3.87z"/>
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-foreground dark:text-gray-200">iCloud Mail (Apple)</h4>
                                        <p class="text-sm text-muted-foreground mt-1">Apple requer senha específica para apps</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-3">
                                    <h5 class="font-medium text-foreground">Passo a passo:</h5>
                                    <ol class="list-decimal list-inside space-y-2 text-sm text-muted-foreground">
                                        <li>Acesse <a href="https://appleid.apple.com" target="_blank" class="text-primary hover:underline">appleid.apple.com</a></li>
                                        <li>Faça login com seu Apple ID</li>
                                        <li>Vá em <strong>Segurança</strong></li>
                                        <li>Em "Senhas específicas de apps", clique em <strong>Gerar senha</strong></li>
                                        <li>Digite "GestorPro" como rótulo</li>
                                        <li>Copie a senha gerada (formato: xxxx-xxxx-xxxx-xxxx)</li>
                                    </ol>
                                </div>

                                <div class="p-4 bg-muted/50 dark:bg-gray-700/50 rounded-lg">
                                    <h5 class="font-medium text-foreground mb-2">Configurações SMTP:</h5>
                                    <div class="grid grid-cols-2 gap-2 text-sm">
                                        <span class="text-muted-foreground">Servidor:</span>
                                        <code class="text-foreground font-mono">smtp.mail.me.com</code>
                                        <span class="text-muted-foreground">Porta:</span>
                                        <code class="text-foreground font-mono">587 (TLS)</code>
                                        <span class="text-muted-foreground">Usuário:</span>
                                        <code class="text-foreground font-mono">seu-email@icloud.com</code>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="flex items-center justify-between p-6 border-t border-border border-border bg-muted/50 bg-card/50 rounded-b-2xl">
                            <p class="text-xs text-muted-foreground">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Suas credenciais são armazenadas de forma segura
                            </p>
                            <button 
                                @click="showHelpModal = false"
                                class="btn-primary"
                            >
                                Entendi
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                {{-- Email para notificações --}}
                <div>
                    <label for="notification_email" class="block text-sm font-medium text-foreground mb-2">
                        Email para Notificações
                    </label>
                    <p class="text-sm text-muted-foreground mb-3">
                        Informe um email alternativo para receber notificações (deixe vazio para usar o email da conta)
                    </p>
                    <input 
                        type="email" 
                        id="notification_email"
                        x-model="notificationEmail"
                        placeholder="exemplo@email.com"
                        class="input w-full sm:w-96"
                    >
                </div>

                {{-- Preferências de Notificação --}}
                <div class="border-t border-border border-border pt-6">
                    <h4 class="text-sm font-medium text-foreground mb-4">Preferências de Notificação</h4>
                    
                    <div class="space-y-4">
                        {{-- Notificações por Email --}}
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <div class="relative flex items-center">
                                <input 
                                    type="checkbox" 
                                    x-model="emailNotifications"
                                    class="sr-only peer"
                                >
                                <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-border after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </div>
                            <div class="flex-1">
                                <span class="text-sm font-medium text-foreground">Notificações por Email</span>
                                <p class="text-sm text-muted-foreground">Receber emails sobre atualizações importantes e lembretes</p>
                            </div>
                        </label>

                        {{-- Relatório Semanal --}}
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <div class="relative flex items-center">
                                <input 
                                    type="checkbox" 
                                    x-model="weeklyReport"
                                    class="sr-only peer"
                                >
                                <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-border after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </div>
                            <div class="flex-1">
                                <span class="text-sm font-medium text-foreground">Relatório Semanal</span>
                                <p class="text-sm text-muted-foreground">Receber um resumo semanal das suas horas trabalhadas</p>
                            </div>
                        </label>

                        {{-- Resumo Mensal --}}
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <div class="relative flex items-center">
                                <input 
                                    type="checkbox" 
                                    x-model="monthlySummary"
                                    class="sr-only peer"
                                >
                                <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-border after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </div>
                            <div class="flex-1">
                                <span class="text-sm font-medium text-foreground">Resumo Mensal</span>
                                <p class="text-sm text-muted-foreground">Receber um relatório completo no final de cada mês</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Botão Salvar --}}
                <div class="flex justify-end pt-4 border-t border-border border-border">
                    <button 
                        @click="saveSettings()"
                        type="button"
                        class="btn-primary"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Salvar Configurações
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
