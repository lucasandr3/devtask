{{-- Sidebar Component --}}
<aside 
    x-data="{ 
        collapsed: localStorage.getItem('sidebar-collapsed') === 'true',
        toggleCollapse() {
            this.collapsed = !this.collapsed;
            localStorage.setItem('sidebar-collapsed', this.collapsed);
            document.documentElement.setAttribute('data-sidebar-collapsed', this.collapsed ? 'true' : 'false');
            window.dispatchEvent(new CustomEvent('sidebar-toggled', { detail: { collapsed: this.collapsed } }));
        }
    }"
    :class="collapsed ? 'w-20' : 'w-64'"
    class="fixed left-0 top-0 h-full bg-white border-r border-gray-200 z-40 transition-all duration-300 ease-in-out hidden lg:flex flex-col dark-sidebar"
>
    {{-- Logo Section --}}
    <div class="h-16 flex items-center border-b border-gray-200 dark:border-slate-700 relative"
        :class="collapsed ? 'justify-center px-3' : 'justify-between px-4'">
        <a href="{{ route('painel') }}" class="flex items-center gap-3 group overflow-hidden">
            <div class="p-2 bg-primary-600 rounded-xl group-hover:bg-primary-700 transition-colors duration-200 flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
            </div>
            <span 
                x-show="!collapsed" 
                x-transition:enter="transition-opacity duration-200"
                x-transition:leave="transition-opacity duration-100"
                class="text-lg font-bold text-gray-900 dark:text-white whitespace-nowrap"
            >
                DevTask
            </span>
        </a>
        <button 
            @click="toggleCollapse()" 
            class="p-1.5 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors"
            :class="collapsed ? 'absolute -right-4 top-1/2 -translate-y-1/2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 shadow-sm z-10' : ''"
        >
            <svg class="w-5 h-5 transition-transform duration-300" :class="collapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
            </svg>
        </button>
    </div>

    {{-- Navigation Links --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        {{-- Main Section --}}
        <div class="mb-6">
            <p 
                x-show="!collapsed" 
                class="px-3 mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
                Principal
            </p>

            <x-sidebar-link :href="route('painel')" :active="request()->routeIs('painel')" :collapsed="false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                    </svg>
                </x-slot>
                Dashboard
            </x-sidebar-link>

            <x-sidebar-link :href="route('emails.index')" :active="request()->routeIs('emails.*')" :collapsed="false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </x-slot>
                Emails
            </x-sidebar-link>
        </div>

        {{-- Horas Section --}}
        <div class="mb-6">
            <p 
                x-show="!collapsed" 
                class="px-3 mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
                Horas
            </p>

            <x-sidebar-link :href="route('horas.registrar')" :active="request()->routeIs('horas.registrar')" :collapsed="false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </x-slot>
                Registrar Hora
            </x-sidebar-link>

            <x-sidebar-link :href="route('horas.index')" :active="request()->routeIs('horas.index')" :collapsed="false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </x-slot>
                Espelho Mensal
            </x-sidebar-link>
        </div>

        {{-- Trabalho Section --}}
        <div class="mb-6">
            <p 
                x-show="!collapsed" 
                class="px-3 mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
                Trabalho
            </p>

            <x-sidebar-link :href="route('tarefas.index')" :active="request()->routeIs('tarefas.*')" :collapsed="false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </x-slot>
                Tarefas
            </x-sidebar-link>

            <x-sidebar-link :href="route('pull-requests.index')" :active="request()->routeIs('pull-requests.*')" :collapsed="false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </x-slot>
                Pull Requests
            </x-sidebar-link>
        </div>

        {{-- Gestão Section --}}
        <div class="mb-6">
            <p 
                x-show="!collapsed" 
                class="px-3 mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
                Gestão
            </p>

            <x-sidebar-link :href="route('contratos.index')" :active="request()->routeIs('contratos.*')" :collapsed="false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </x-slot>
                Contratos
            </x-sidebar-link>

            <x-sidebar-link :href="route('relatorios.index')" :active="request()->routeIs('relatorios.*')" :collapsed="false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </x-slot>
                Relatórios
            </x-sidebar-link>
        </div>

        {{-- Financeiro Section --}}
        <div class="mb-6">
            <p 
                x-show="!collapsed" 
                class="px-3 mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
                Financeiro
            </p>

            <x-sidebar-link :href="route('notas-fiscais.index')" :active="request()->routeIs('notas-fiscais.*')" :collapsed="false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </x-slot>
                Notas Fiscais
            </x-sidebar-link>

            <!-- <x-sidebar-link :href="route('das.index')" :active="request()->routeIs('das.*')" :collapsed="false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </x-slot>
                DAS
            </x-sidebar-link> -->

            <!-- <x-sidebar-link :href="route('declaracao-anual.index')" :active="request()->routeIs('declaracao-anual.*')" :collapsed="false">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </x-slot>
                Declaração Anual
            </x-sidebar-link> -->
        </div>
    </nav>

    {{-- Bottom Section --}}
    <div class="border-t border-gray-200 dark:border-slate-700 p-3">
        {{-- User Section --}}
        <x-dropdown align="top-left" width="48">
            <x-slot name="trigger">
                <button 
                    class="w-full flex items-center gap-3 p-2 rounded-xl text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors"
                    :class="collapsed ? 'justify-center' : ''"
                >
                    <div class="w-9 h-9 rounded-full bg-primary-100 dark:bg-slate-700 flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-semibold text-primary-700 dark:text-primary-300">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </span>
                    </div>
                    <div x-show="!collapsed" class="flex-1 text-left overflow-hidden">
                        <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <svg x-show="!collapsed" class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('perfil.editar')">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Minha Conta
                </x-dropdown-link>

                <x-dropdown-link :href="route('configuracoes.index')">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Configurações
                </x-dropdown-link>

                <form method="POST" action="{{ route('sair') }}">
                    @csrf
                    <x-dropdown-link :href="route('sair')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Sair
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</aside>

{{-- Mobile Sidebar Overlay --}}
<div 
    x-data="{ mobileOpen: false }"
    x-on:toggle-mobile-sidebar.window="mobileOpen = !mobileOpen"
    class="lg:hidden"
>
    {{-- Overlay --}}
    <div 
        x-show="mobileOpen" 
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="mobileOpen = false"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40"
    ></div>

    {{-- Mobile Sidebar --}}
    <aside 
        x-show="mobileOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed left-0 top-0 h-full w-72 bg-white border-r border-gray-200 z-50 flex flex-col dark-sidebar"
    >
        {{-- Logo Section --}}
        <div class="h-16 flex items-center justify-between px-4 border-b border-gray-200 dark:border-slate-700">
            <a href="{{ route('painel') }}" class="flex items-center gap-3">
                <div class="p-2 bg-primary-600 rounded-xl">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <span class="text-lg font-bold text-gray-900 dark:text-white">DevTask</span>
            </a>
            <button 
                @click="mobileOpen = false"
                class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-800"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Navigation Links --}}
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            {{-- Main Section --}}
            <div class="mb-6">
                <p class="px-3 mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Principal</p>
                
                <a href="{{ route('painel') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('painel') ? 'bg-primary-50 text-primary-700 sidebar-link-active' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 sidebar-link-hover' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('emails.index') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('emails.*') ? 'bg-primary-50 text-primary-700 sidebar-link-active' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 sidebar-link-hover' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Emails
                </a>
            </div>

            {{-- Horas Section --}}
            <div class="mb-6">
                <p class="px-3 mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Horas</p>

                <a href="{{ route('horas.registrar') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('horas.registrar') ? 'bg-primary-50 text-primary-700 sidebar-link-active' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 sidebar-link-hover' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Registrar Hora
                </a>

                <a href="{{ route('horas.index') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('horas.index') ? 'bg-primary-50 text-primary-700 sidebar-link-active' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 sidebar-link-hover' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Espelho Mensal
                </a>
            </div>

            {{-- Trabalho Section --}}
            <div class="mb-6">
                <p class="px-3 mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trabalho</p>

                <a href="{{ route('tarefas.index') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('tarefas.*') ? 'bg-primary-50 text-primary-700 sidebar-link-active' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 sidebar-link-hover' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Tarefas
                </a>

                <a href="{{ route('pull-requests.index') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('pull-requests.*') ? 'bg-primary-50 text-primary-700 sidebar-link-active' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 sidebar-link-hover' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Pull Requests
                </a>
            </div>

            {{-- Gestão Section --}}
            <div class="mb-6">
                <p class="px-3 mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Gestão</p>

                <a href="{{ route('contratos.index') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('contratos.*') ? 'bg-primary-50 text-primary-700 sidebar-link-active' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 sidebar-link-hover' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Contratos
                </a>

                <a href="{{ route('relatorios.index') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('relatorios.*') ? 'bg-primary-50 text-primary-700 sidebar-link-active' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 sidebar-link-hover' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Relatórios
                </a>
            </div>

            {{-- Financeiro Section --}}
            <div class="mb-6">
                <p class="px-3 mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Financeiro</p>

                <a href="{{ route('notas-fiscais.index') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('notas-fiscais.*') ? 'bg-primary-50 text-primary-700 sidebar-link-active' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 sidebar-link-hover' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Notas Fiscais
                </a>

                <!-- <a href="{{ route('das.index') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('das.*') ? 'bg-primary-50 text-primary-700 sidebar-link-active' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 sidebar-link-hover' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    DAS
                </a>

                <a href="{{ route('declaracao-anual.index') }}" @click="mobileOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('declaracao-anual.*') ? 'bg-primary-50 text-primary-700 sidebar-link-active' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 sidebar-link-hover' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Declaração Anual
                </a> -->
            </div>
        </nav>

        {{-- Bottom Section --}}
        <div class="border-t border-gray-200 p-3 dark-border">
            {{-- User Section --}}
            <div class="flex items-center gap-3 p-2 rounded-xl bg-gray-50 dark-surface">
                <div class="w-9 h-9 rounded-full bg-primary-100 flex items-center justify-center dark-surface-alt">
                    <span class="text-sm font-semibold text-primary-700 dark:text-primary-300">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </span>
                </div>
                <div class="flex-1 overflow-hidden">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <div class="mt-2 space-y-1">
                <a href="{{ route('perfil.editar') }}" @click="mobileOpen = false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 sidebar-link-hover">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Minha Conta
                </a>
                <a href="{{ route('configuracoes.index') }}" @click="mobileOpen = false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 sidebar-link-hover">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Configurações
                </a>
                <form method="POST" action="{{ route('sair') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </aside>
</div>
