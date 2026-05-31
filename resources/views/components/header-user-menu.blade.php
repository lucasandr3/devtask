@props([
    'dropUp' => false,
    'showText' => false,
    'fullWidth' => false,
])

@php
    $company = \App\Support\CurrentCompany::get();
    $companyName = $company?->name ?? Auth::user()->name;
    $initials = strtoupper(substr($companyName, 0, 1));
    $dropdownAlign = $dropUp ? 'top-left' : 'right';
    $dropdownWidth = $fullWidth ? 'full' : '56';
@endphp

<x-dropdown :align="$dropdownAlign" :width="$dropdownWidth" contentClasses="p-1">
    <x-slot name="trigger">
        <button
            type="button"
            @class([
                'flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-sidebar-accent transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring',
                'w-full' => $fullWidth,
            ])
        >
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-primary-foreground text-xs font-semibold flex-shrink-0">
                {{ $initials }}
            </div>
            <div @class([
                'text-left min-w-0',
                $showText ? 'block flex-1' : 'hidden md:block',
            ])>
                <p @class([
                    'text-sm font-semibold leading-none truncate',
                    $fullWidth ? 'max-w-none' : 'max-w-[140px]',
                ])>{{ $companyName }}</p>
                <p @class([
                    'text-xs text-muted-foreground truncate mt-1',
                    $fullWidth ? 'max-w-none' : 'max-w-[140px]',
                ])>{{ Auth::user()->email }}</p>
            </div>
            <svg @class([
                'h-4 w-4 text-muted-foreground flex-shrink-0',
                $showText ? 'block' : 'hidden sm:block',
            ]) fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
            </svg>
        </button>
    </x-slot>
    <x-slot name="content">
        @unless($showText)
            <div class="px-2 py-1.5 md:hidden border-b border-border mb-1">
                <p class="text-sm font-semibold truncate">{{ $companyName }}</p>
                <p class="text-xs text-muted-foreground truncate">{{ Auth::user()->email }}</p>
            </div>
        @endunless
        <x-dropdown-link :href="route('perfil.editar')" class="gap-2">
            <x-ui.icon name="user" class="size-4 text-muted-foreground" />
            Minha Conta
        </x-dropdown-link>
        <x-dropdown-link :href="route('configuracoes.index')" class="gap-2">
            <x-ui.icon name="settings" class="size-4 text-muted-foreground" />
            Configurações
        </x-dropdown-link>
        <form method="POST" action="{{ route('sair') }}">
            @csrf
            <x-dropdown-link :href="route('sair')" class="menu-link-danger gap-2" onclick="event.preventDefault(); this.closest('form').submit();">
                <x-ui.icon name="logout" class="size-4 text-muted-foreground" />
                Sair
            </x-dropdown-link>
        </form>
    </x-slot>
</x-dropdown>
