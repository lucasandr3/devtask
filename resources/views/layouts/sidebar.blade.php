{{-- Sidebar Component --}}
<aside 
    x-data="{ 
        collapsed: localStorage.getItem('sidebar-collapsed') === 'true',
        toggleCollapse() {
            this.collapsed = !this.collapsed;
            localStorage.setItem('sidebar-collapsed', this.collapsed);
            document.documentElement.setAttribute('data-sidebar-collapsed', this.collapsed ? 'true' : 'false');
        }
    }"
    x-init="document.documentElement.setAttribute('data-sidebar-collapsed', collapsed ? 'true' : 'false')"
    @toggle-sidebar-collapse.window="toggleCollapse()"
    :class="collapsed ? 'w-16 sidebar-collapsed' : 'w-64'"
    class="sidebar-panel fixed left-0 top-14 h-[calc(100vh-3.5rem)] bg-sidebar text-sidebar-foreground border-r border-sidebar-border z-40 transition-all duration-300 ease-in-out hidden lg:flex flex-col"
>
    <nav class="flex-1 overflow-y-auto py-4 px-2 space-y-4">
        <div>
            <p x-show="!collapsed" class="px-2 mb-1 text-xs font-medium text-muted-foreground">Principal</p>
            <x-sidebar-link :href="route('painel')" :active="request()->routeIs('painel')">
                <x-slot name="icon"><x-ui.icon name="dashboard" /></x-slot>
                Dashboard
            </x-sidebar-link>
        </div>

        <div>
            <p x-show="!collapsed" class="px-2 mb-1 text-xs font-medium text-muted-foreground">Projetos</p>
            <x-sidebar-link :href="route('projetos.index')" :active="request()->routeIs('projetos.*')">
                <x-slot name="icon"><x-ui.icon name="projects" /></x-slot>
                Projetos
            </x-sidebar-link>
            <x-sidebar-link :href="route('tarefas.index')" :active="request()->routeIs('tarefas.*')">
                <x-slot name="icon"><x-ui.icon name="tasks" /></x-slot>
                Tarefas
            </x-sidebar-link>
        </div>

        <div>
            <p x-show="!collapsed" class="px-2 mb-1 text-xs font-medium text-muted-foreground">Ponto</p>
            <x-sidebar-link :href="route('horas.registrar')" :active="request()->routeIs('horas.registrar')">
                <x-slot name="icon"><x-ui.icon name="clock-in" /></x-slot>
                Registrar Ponto
            </x-sidebar-link>
            <x-sidebar-link :href="route('horas.index')" :active="request()->routeIs('horas.index') || request()->routeIs('horas.criar') || request()->routeIs('horas.editar')">
                <x-slot name="icon"><x-ui.icon name="timesheet" /></x-slot>
                Espelho de Ponto
            </x-sidebar-link>
            <x-sidebar-link :href="route('relatorios-mensais.index')" :active="request()->routeIs('relatorios-mensais.index')">
                <x-slot name="icon"><x-ui.icon name="report" /></x-slot>
                Meu Relatório Mensal
            </x-sidebar-link>
        </div>

        @if(\App\Support\CurrentCompany::canViewFinance())
            <div>
                <p x-show="!collapsed" class="px-2 mb-1 text-xs font-medium text-muted-foreground">Financeiro</p>
                <x-sidebar-link :href="route('financeiro.index')" :active="request()->routeIs('financeiro.index')">
                    <x-slot name="icon"><x-ui.icon name="finance" /></x-slot>
                    Visão Geral
                </x-sidebar-link>
                <x-sidebar-link :href="route('notas-fiscais.index')" :active="request()->routeIs('notas-fiscais.*')">
                    <x-slot name="icon"><x-ui.icon name="invoice" /></x-slot>
                    Faturamento
                </x-sidebar-link>
                <x-sidebar-link :href="route('das.index')" :active="request()->routeIs('das.*')">
                    <x-slot name="icon"><x-ui.icon name="report" /></x-slot>
                    Tributos
                </x-sidebar-link>
                <x-sidebar-link :href="route('financeiro.lancamentos.index')" :active="request()->routeIs('financeiro.lancamentos.*')">
                    <x-slot name="icon"><x-ui.icon name="table" /></x-slot>
                    Contas a pagar/receber
                </x-sidebar-link>
                <x-sidebar-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')">
                    <x-slot name="icon"><x-ui.icon name="clients" /></x-slot>
                    Clientes
                </x-sidebar-link>
                <x-sidebar-link :href="route('declaracao-anual.index')" :active="request()->routeIs('declaracao-anual.*')">
                    <x-slot name="icon"><x-ui.icon name="report" /></x-slot>
                    Fechamento anual
                </x-sidebar-link>
                <x-sidebar-link :href="route('relatorios.financeiro')" :active="request()->routeIs('relatorios.financeiro*')">
                    <x-slot name="icon"><x-ui.icon name="report" /></x-slot>
                    Relatório detalhado
                </x-sidebar-link>
            </div>
        @endif

        @if(\App\Support\CurrentCompany::canViewCompanyReports() || \App\Support\CurrentCompany::canManageTeam())
            <div>
                <p x-show="!collapsed" class="px-2 mb-1 text-xs font-medium text-muted-foreground">Gestão</p>
                @if(\App\Support\CurrentCompany::canViewCompanyReports())
                    <x-sidebar-link :href="route('relatorios.horas-empresa')" :active="request()->routeIs('relatorios.horas-empresa')">
                        <x-slot name="icon"><x-ui.icon name="company-hours" /></x-slot>
                        Horas da Empresa
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('relatorios-mensais.aprovacoes')" :active="request()->routeIs('relatorios-mensais.aprovacoes')">
                        <x-slot name="icon"><x-ui.icon name="approve" /></x-slot>
                        Aprovar Relatórios
                    </x-sidebar-link>
                @endif
                @if(\App\Support\CurrentCompany::canManageTeam())
                    <x-sidebar-link :href="route('contatos-site.index')" :active="request()->routeIs('contatos-site.*')">
                        <x-slot name="icon"><x-ui.icon name="mail" /></x-slot>
                        Contatos do site
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('equipe.index')" :active="request()->routeIs('equipe.*')">
                        <x-slot name="icon"><x-ui.icon name="team" /></x-slot>
                        Equipe
                    </x-sidebar-link>
                @endif
            </div>
        @endif
    </nav>

    <div class="border-t border-sidebar-border p-2 mt-auto">
        <form method="POST" action="{{ route('sair') }}">
            @csrf
            <button
                type="submit"
                class="menu-link-danger flex items-center gap-3 px-3 py-2 w-full rounded-md text-sm text-sidebar-foreground"
                :class="collapsed ? 'justify-center ui-tooltip ui-tooltip-right' : ''"
                x-bind:data-tooltip="collapsed ? 'Sair' : null"
                x-bind:aria-label="collapsed ? 'Sair' : null"
            >
                <span class="flex-shrink-0"><x-ui.icon name="logout" /></span>
                <span x-show="!collapsed" x-transition.opacity>Sair</span>
            </button>
        </form>
    </div>
</aside>

{{-- Mobile Sidebar --}}
<div class="lg:hidden">
    <div
        x-show="mobileSidebarOpen"
        x-transition.opacity
        @click="closeMobileSidebar()"
        class="fixed inset-0 bg-background/80 backdrop-blur-sm z-[55]"
        style="display: none;"
    ></div>
    <aside
        x-show="mobileSidebarOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed left-0 top-0 h-full w-72 bg-sidebar text-sidebar-foreground border-r border-sidebar-border z-[60] flex flex-col"
        style="display: none;"
    >
        <div class="h-14 flex items-center justify-between px-4 border-b border-sidebar-border">
            <x-ui.logo :href="route('painel')" size="sm" text-class="text-sm font-bold" />
            <button type="button" @click="closeMobileSidebar()" class="inline-flex items-center justify-center rounded-md h-8 w-8 hover:bg-sidebar-accent transition-colors" aria-label="Fechar menu">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <nav class="flex-1 overflow-y-auto py-4 px-2 space-y-1">
            @php
                $links = [
                    ['route' => 'painel', 'label' => 'Dashboard', 'match' => 'painel', 'icon' => 'dashboard'],
                    ['route' => 'projetos.index', 'label' => 'Projetos', 'match' => 'projetos.*', 'icon' => 'projects'],
                    ['route' => 'tarefas.index', 'label' => 'Tarefas', 'match' => 'tarefas.*', 'icon' => 'tasks'],
                    ['route' => 'horas.registrar', 'label' => 'Registrar Ponto', 'match' => 'horas.registrar', 'icon' => 'clock-in'],
                    ['route' => 'horas.index', 'label' => 'Espelho de Ponto', 'match' => 'horas.index', 'icon' => 'timesheet'],
                    ['route' => 'relatorios-mensais.index', 'label' => 'Meu Relatório Mensal', 'match' => 'relatorios-mensais.index', 'icon' => 'report'],
                ];
                if (\App\Support\CurrentCompany::canViewCompanyReports()) {
                    $links[] = ['route' => 'relatorios.horas-empresa', 'label' => 'Horas da Empresa', 'match' => 'relatorios.horas-empresa', 'icon' => 'company-hours'];
                    $links[] = ['route' => 'relatorios-mensais.aprovacoes', 'label' => 'Aprovar Relatórios', 'match' => 'relatorios-mensais.aprovacoes', 'icon' => 'approve'];
                }
                if (\App\Support\CurrentCompany::canManageTeam()) {
                    $links[] = ['route' => 'contatos-site.index', 'label' => 'Contatos do site', 'match' => 'contatos-site.*', 'icon' => 'mail'];
                    $links[] = ['route' => 'equipe.index', 'label' => 'Equipe', 'match' => 'equipe.*', 'icon' => 'team'];
                }
                if (\App\Support\CurrentCompany::canViewFinance()) {
                    $links[] = ['route' => 'financeiro.index', 'label' => 'Financeiro', 'match' => 'financeiro.index', 'icon' => 'finance'];
                    $links[] = ['route' => 'notas-fiscais.index', 'label' => 'Faturamento', 'match' => 'notas-fiscais.*', 'icon' => 'invoice'];
                    $links[] = ['route' => 'clientes.index', 'label' => 'Clientes', 'match' => 'clientes.*', 'icon' => 'clients'];
                }
            @endphp
            @foreach($links as $link)
                <a href="{{ route($link['route']) }}" @click="closeMobileSidebar()"
                   class="flex items-center gap-3 px-3 py-2 rounded-md text-sm transition-colors {{ request()->routeIs($link['match']) ? 'bg-primary/10 text-primary font-medium dark:bg-primary/15' : 'hover:bg-sidebar-accent hover:text-sidebar-accent-foreground' }}">
                    <x-ui.icon :name="$link['icon']" />
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>
        <div class="border-t border-sidebar-border p-3 space-y-2">
            <x-header-user-menu drop-up show-text full-width />
            <form method="POST" action="{{ route('sair') }}">
                @csrf
                <button type="submit" class="menu-link-danger flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm text-sidebar-foreground">
                    <x-ui.icon name="logout" />
                    Sair
                </button>
            </form>
        </div>
    </aside>
</div>
