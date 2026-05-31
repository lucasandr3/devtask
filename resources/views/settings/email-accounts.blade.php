<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Contas de Email</h2>
    </x-slot>

    <x-ui.page-back :href="route('configuracoes.index')" class="mb-6" />

    <div class="space-y-6" x-data="{ 
        showAddModal: false,
        showEditModal: false,
        editAccount: null,
        provider: 'gmail',
        showAdvanced: false,
        providerConfigs: {
            gmail: { smtp_host: 'smtp.gmail.com', smtp_port: 587, imap_host: 'imap.gmail.com', imap_port: 993 },
            outlook: { smtp_host: 'smtp-mail.outlook.com', smtp_port: 587, imap_host: 'outlook.office365.com', imap_port: 993 },
            yahoo: { smtp_host: 'smtp.mail.yahoo.com', smtp_port: 587, imap_host: 'imap.mail.yahoo.com', imap_port: 993 },
            icloud: { smtp_host: 'smtp.mail.me.com', smtp_port: 587, imap_host: 'imap.mail.me.com', imap_port: 993 },
            custom: { smtp_host: '', smtp_port: 587, imap_host: '', imap_port: 993 }
        },
        getConfig(type) {
            return this.providerConfigs[this.provider]?.[type] || '';
        }
    }">
        {{-- Lista de Contas --}}
        <div class="card">
            <div class="p-6 border-b border-border border-border">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-primary/10 rounded-lg">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-foreground">Suas Contas de Email</h3>
                            <p class="text-sm text-muted-foreground">Gerencie suas contas de email para enviar e receber mensagens</p>
                        </div>
                    </div>
                    <button @click="showAddModal = true" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Adicionar Conta
                    </button>
                </div>
            </div>

            @if($accounts->isEmpty())
                <div class="p-12 text-center">
                    <div class="mx-auto w-16 h-16 bg-muted dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-medium text-foreground mb-2">Nenhuma conta configurada</h4>
                    <p class="text-muted-foreground mb-4">Adicione uma conta de email para começar a enviar e receber mensagens.</p>
                    <button @click="showAddModal = true" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Adicionar Primeira Conta
                    </button>
                </div>
            @else
                <div class="divide-y divide-border dark:divide-gray-700">
                    @foreach($accounts as $account)
                        <div class="p-4 sm:p-6 flex items-center justify-between hover:bg-muted/50 dark:hover:bg-gray-800/50 transition-colors">
                            <div class="flex items-center gap-4">
                                {{-- Provider Icon --}}
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center
                                    @if($account->provider === 'gmail') bg-red-100 dark:bg-red-900/30
                                    @elseif($account->provider === 'outlook') bg-blue-100 dark:bg-blue-900/30
                                    @elseif($account->provider === 'yahoo') bg-purple-100 dark:bg-purple-900/30
                                    @elseif($account->provider === 'icloud') bg-muted dark:bg-gray-700
                                    @else bg-primary/10
                                    @endif
                                ">
                                    @if($account->provider === 'gmail')
                                        <svg class="w-6 h-6 text-red-500" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 19.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/>
                                        </svg>
                                    @elseif($account->provider === 'outlook')
                                        <svg class="w-6 h-6 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M24 7.387v10.478c0 .23-.08.424-.238.576-.158.154-.352.23-.58.23h-8.547v-6.959l1.6 1.229c.102.086.234.13.397.13.176 0 .324-.054.445-.163l.604-.545c.085-.086.138-.18.158-.28.026-.1.017-.2-.026-.297-.043-.1-.116-.18-.218-.244l-3.787-2.89v-.012l-.63-.477c-.104-.092-.228-.137-.37-.137-.146 0-.274.055-.384.163l-.633.478v.011l-3.787 2.891c-.103.065-.175.145-.218.244-.043.1-.052.198-.026.298.026.1.078.193.159.28l.603.544c.122.11.27.164.445.164.163 0 .295-.045.397-.131l1.6-1.228v6.958H.818c-.228 0-.422-.076-.58-.23C.08 18.29 0 18.096 0 17.866V7.387c0-.154.044-.299.133-.437.089-.137.2-.24.333-.308L11.35.168c.183-.112.387-.168.611-.168.219 0 .42.056.604.168l10.893 6.474c.133.069.244.17.333.308.089.138.133.283.133.437H24z"/>
                                        </svg>
                                    @elseif($account->provider === 'yahoo')
                                        <svg class="w-6 h-6 text-purple-500" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M11.996 0C5.381 0 0 5.381 0 11.996 0 18.62 5.381 24 11.996 24c6.623 0 12.004-5.38 12.004-12.004C24 5.381 18.619 0 11.996 0zm4.476 7.554l-2.528 5.9 2.702 5.98h-2.482l-2.445-5.726-2.453 5.726H6.786l2.695-5.98-2.528-5.9h2.482l2.204 5.152 2.351-5.152z"/>
                                        </svg>
                                    @elseif($account->provider === 'icloud')
                                        <svg class="w-6 h-6 text-muted-foreground" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M13.762 3.75c1.658 0 3.217.652 4.387 1.835a6.22 6.22 0 0 1 1.888 4.475c.002.078-.003.156-.008.233a5.471 5.471 0 0 1 3.721 5.194c0 3.024-2.462 5.488-5.488 5.488H6.238A6.238 6.238 0 0 1 0 14.737c0-2.598 1.614-4.933 4.03-5.856a6.223 6.223 0 0 1 5.862-8.131h3.87z"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    @endif
                                </div>

                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-medium text-foreground">{{ $account->name }}</h4>
                                        @if($account->is_default)
                                            <span class="px-2 py-0.5 text-xs font-medium bg-primary-100 text-primary-700 bg-primary/10 dark:text-primary-300 rounded-full">
                                                Padrão
                                            </span>
                                        @endif
                                        @if(!$account->is_active)
                                            <span class="px-2 py-0.5 text-xs font-medium bg-muted text-muted-foreground dark:bg-gray-700 text-muted-foreground rounded-full">
                                                Inativo
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-muted-foreground">{{ $account->email }}</p>
                                    <p class="text-xs text-gray-400 dark:text-muted-foreground mt-1">
                                        {{ ucfirst($account->provider) }} • 
                                        @if($account->last_sync_at)
                                            Última sincronização: {{ $account->last_sync_at->diffForHumans() }}
                                        @else
                                            Nunca sincronizado
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                {{-- Sincronizar --}}
                                <form action="{{ route('configuracoes.emails.sync', $account) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 text-gray-400 hover:text-green-600 dark:hover:text-green-400 rounded-lg hover:bg-muted dark:hover:bg-gray-700 transition-colors ui-tooltip ui-tooltip-top" data-tooltip="Sincronizar emails" aria-label="Sincronizar emails">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                    </button>
                                </form>

                                @if(!$account->is_default)
                                    <form action="{{ route('configuracoes.emails.default', $account) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 text-gray-400 hover:text-primary rounded-lg hover:bg-muted dark:hover:bg-gray-700 transition-colors ui-tooltip ui-tooltip-top" data-tooltip="Definir como padrão" aria-label="Definir como padrão">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                                
                                <a href="{{ route('configuracoes.emails.edit', $account) }}" class="p-2 text-gray-400 hover:text-muted-foreground dark:hover:text-gray-300 rounded-lg hover:bg-muted dark:hover:bg-gray-700 transition-colors ui-tooltip ui-tooltip-top" data-tooltip="Editar conta" aria-label="Editar conta">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>

                                <form action="{{ route('configuracoes.emails.destroy', $account) }}" method="POST" class="inline" data-confirm="Tem certeza que deseja excluir esta conta?" data-confirm-title="Excluir conta?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-muted dark:hover:bg-gray-700 transition-colors ui-tooltip ui-tooltip-top" data-tooltip="Excluir conta" aria-label="Excluir conta">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Modal Adicionar Conta --}}
        <div 
            x-show="showAddModal" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
        >
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showAddModal = false"></div>
            
            <div class="flex min-h-full items-center justify-center p-4">
                <div 
                    x-show="showAddModal"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative w-full max-w-2xl bg-white bg-card rounded-2xl shadow-2xl"
                    @click.away="showAddModal = false"
                >
                    <form action="{{ route('configuracoes.emails.store') }}" method="POST">
                        @csrf
                        
                        {{-- Header --}}
                        <div class="flex items-center justify-between p-6 border-b border-border border-border">
                            <h3 class="text-lg font-semibold text-foreground">Adicionar Conta de Email</h3>
                            <button type="button" @click="showAddModal = false" class="p-2 text-gray-400 hover:text-muted-foreground dark:hover:text-gray-300 rounded-lg hover:bg-muted dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="p-6 space-y-6 max-h-[60vh] overflow-y-auto">
                            {{-- Seleção de Provedor --}}
                            <div>
                                <label class="block text-sm font-medium text-foreground mb-3">Provedor de Email</label>
                                <div class="grid grid-cols-5 gap-3">
                                    @foreach(['gmail' => 'Gmail', 'outlook' => 'Outlook', 'yahoo' => 'Yahoo', 'icloud' => 'iCloud', 'custom' => 'Outro'] as $key => $label)
                                        <label class="relative">
                                            <input type="radio" name="provider" value="{{ $key }}" x-model="provider" class="sr-only peer">
                                            <div class="flex flex-col items-center p-3 rounded-xl border-2 cursor-pointer transition-all
                                                peer-checked:border-primary peer-checked:bg-primary/10 dark:peer-checked:bg-primary-900/20
                                                border-border border-border hover:border-border hover:border-border">
                                                @if($key === 'gmail')
                                                    <svg class="w-6 h-6 text-red-500 mb-1" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 19.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/>
                                                    </svg>
                                                @elseif($key === 'outlook')
                                                    <svg class="w-6 h-6 text-blue-500 mb-1" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M24 7.387v10.478c0 .23-.08.424-.238.576-.158.154-.352.23-.58.23h-8.547v-6.959l1.6 1.229c.102.086.234.13.397.13.176 0 .324-.054.445-.163l.604-.545c.085-.086.138-.18.158-.28.026-.1.017-.2-.026-.297-.043-.1-.116-.18-.218-.244l-3.787-2.89v-.012l-.63-.477c-.104-.092-.228-.137-.37-.137-.146 0-.274.055-.384.163l-.633.478v.011l-3.787 2.891c-.103.065-.175.145-.218.244-.043.1-.052.198-.026.298.026.1.078.193.159.28l.603.544c.122.11.27.164.445.164.163 0 .295-.045.397-.131l1.6-1.228v6.958H.818c-.228 0-.422-.076-.58-.23C.08 18.29 0 18.096 0 17.866V7.387c0-.154.044-.299.133-.437.089-.137.2-.24.333-.308L11.35.168c.183-.112.387-.168.611-.168.219 0 .42.056.604.168l10.893 6.474c.133.069.244.17.333.308.089.138.133.283.133.437H24z"/>
                                                    </svg>
                                                @elseif($key === 'yahoo')
                                                    <svg class="w-6 h-6 text-purple-500 mb-1" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M11.996 0C5.381 0 0 5.381 0 11.996 0 18.62 5.381 24 11.996 24c6.623 0 12.004-5.38 12.004-12.004C24 5.381 18.619 0 11.996 0zm4.476 7.554l-2.528 5.9 2.702 5.98h-2.482l-2.445-5.726-2.453 5.726H6.786l2.695-5.98-2.528-5.9h2.482l2.204 5.152 2.351-5.152z"/>
                                                    </svg>
                                                @elseif($key === 'icloud')
                                                    <svg class="w-6 h-6 text-muted-foreground mb-1" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M13.762 3.75c1.658 0 3.217.652 4.387 1.835a6.22 6.22 0 0 1 1.888 4.475c.002.078-.003.156-.008.233a5.471 5.471 0 0 1 3.721 5.194c0 3.024-2.462 5.488-5.488 5.488H6.238A6.238 6.238 0 0 1 0 14.737c0-2.598 1.614-4.933 4.03-5.856a6.223 6.223 0 0 1 5.862-8.131h3.87z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-6 h-6 text-muted-foreground mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                    </svg>
                                                @endif
                                                <span class="text-xs font-medium text-foreground">{{ $label }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Informações Básicas --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-foreground mb-1">Nome da Conta</label>
                                    <input type="text" name="name" id="name" required placeholder="Ex: Gmail Pessoal" class="input">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-foreground mb-1">Endereço de Email</label>
                                    <input type="email" name="email" id="email" required placeholder="seu@email.com" class="input">
                                </div>
                            </div>

                            {{-- Senha --}}
                            <div>
                                <label for="smtp_password" class="block text-sm font-medium text-foreground mb-1">
                                    Senha de App
                                    <span class="text-gray-400 font-normal">(não sua senha normal)</span>
                                </label>
                                <input type="password" name="smtp_password" id="smtp_password" required placeholder="Senha de app gerada pelo provedor" class="input">
                                <input type="hidden" name="imap_password" :value="document.getElementById('smtp_password')?.value">
                                <p class="text-xs text-muted-foreground mt-1">
                                    Use a senha de app gerada nas configurações de segurança do seu provedor de email
                                </p>
                            </div>

                            {{-- Configurações Avançadas --}}
                            <div>
                                <button type="button" @click="showAdvanced = !showAdvanced" class="flex items-center gap-2 text-sm text-primary hover:underline">
                                    <svg class="w-4 h-4 transition-transform" :class="showAdvanced ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    Configurações Avançadas
                                </button>

                                <div x-show="showAdvanced" x-collapse class="mt-4 space-y-4 p-4 bg-muted/50 dark:bg-gray-700/50 rounded-lg">
                                    {{-- SMTP --}}
                                    <div>
                                        <h4 class="text-sm font-medium text-foreground mb-3">Configurações SMTP (Envio)</h4>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs text-muted-foreground mb-1">Servidor SMTP</label>
                                                <input type="text" name="smtp_host" :value="getConfig('smtp_host')" :placeholder="getConfig('smtp_host') || 'smtp.exemplo.com'" class="input text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs text-muted-foreground mb-1">Porta SMTP</label>
                                                <input type="number" name="smtp_port" :value="getConfig('smtp_port')" class="input text-sm" placeholder="587">
                                            </div>
                                            <div>
                                                <label class="block text-xs text-muted-foreground mb-1">Usuário SMTP</label>
                                                <input type="text" name="smtp_username" placeholder="Seu email" class="input text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs text-muted-foreground mb-1">Criptografia</label>
                                                <select name="smtp_encryption" class="input text-sm">
                                                    <option value="tls">TLS</option>
                                                    <option value="ssl">SSL</option>
                                                    <option value="none">Nenhuma</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- IMAP --}}
                                    <div>
                                        <h4 class="text-sm font-medium text-foreground mb-3">Configurações IMAP (Recebimento)</h4>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs text-muted-foreground mb-1">Servidor IMAP</label>
                                                <input type="text" name="imap_host" :value="getConfig('imap_host')" :placeholder="getConfig('imap_host') || 'imap.exemplo.com'" class="input text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs text-muted-foreground mb-1">Porta IMAP</label>
                                                <input type="number" name="imap_port" :value="getConfig('imap_port')" class="input text-sm" placeholder="993">
                                            </div>
                                            <div>
                                                <label class="block text-xs text-muted-foreground mb-1">Usuário IMAP</label>
                                                <input type="text" name="imap_username" placeholder="Seu email" class="input text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs text-muted-foreground mb-1">Criptografia</label>
                                                <select name="imap_encryption" class="input text-sm">
                                                    <option value="ssl" selected>SSL</option>
                                                    <option value="tls">TLS</option>
                                                    <option value="none">Nenhuma</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Definir como padrão --}}
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_default" value="1" class="w-4 h-4 text-primary-600 border-border rounded focus:ring-primary-500">
                                <span class="text-sm text-foreground">Definir como conta padrão</span>
                            </label>
                        </div>

                        {{-- Footer --}}
                        <div class="flex items-center justify-end gap-3 p-6 border-t border-border border-border bg-muted/50 bg-card/50 rounded-b-2xl">
                            <button type="button" @click="showAddModal = false" class="btn-secondary">Cancelar</button>
                            <button type="submit" class="btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Adicionar Conta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
