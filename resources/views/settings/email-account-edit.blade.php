<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="page-title">Editar Conta de Email</h2>
            <a href="{{ route('configuracoes.emails.index') }}" class="btn-secondary text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="card" x-data="{ 
        provider: '{{ $emailAccount->provider }}',
        showAdvanced: true,
        providerConfigs: {
            gmail: { smtp_host: 'smtp.gmail.com', smtp_port: 587, imap_host: 'imap.gmail.com', imap_port: 993 },
            outlook: { smtp_host: 'smtp-mail.outlook.com', smtp_port: 587, imap_host: 'outlook.office365.com', imap_port: 993 },
            yahoo: { smtp_host: 'smtp.mail.yahoo.com', smtp_port: 587, imap_host: 'imap.mail.yahoo.com', imap_port: 993 },
            icloud: { smtp_host: 'smtp.mail.me.com', smtp_port: 587, imap_host: 'imap.mail.me.com', imap_port: 993 },
            custom: { smtp_host: '', smtp_port: 587, imap_host: '', imap_port: 993 }
        }
    }">
        <form action="{{ route('configuracoes.emails.update', $emailAccount) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-6">
                {{-- Seleção de Provedor --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Provedor de Email</label>
                    <div class="grid grid-cols-5 gap-3">
                        @foreach(['gmail' => 'Gmail', 'outlook' => 'Outlook', 'yahoo' => 'Yahoo', 'icloud' => 'iCloud', 'custom' => 'Outro'] as $key => $label)
                            <label class="relative">
                                <input type="radio" name="provider" value="{{ $key }}" x-model="provider" class="sr-only peer" {{ $emailAccount->provider === $key ? 'checked' : '' }}>
                                <div class="flex flex-col items-center p-3 rounded-xl border-2 cursor-pointer transition-all
                                    peer-checked:border-primary-500 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20
                                    border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600">
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
                                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400 mb-1" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M13.762 3.75c1.658 0 3.217.652 4.387 1.835a6.22 6.22 0 0 1 1.888 4.475c.002.078-.003.156-.008.233a5.471 5.471 0 0 1 3.721 5.194c0 3.024-2.462 5.488-5.488 5.488H6.238A6.238 6.238 0 0 1 0 14.737c0-2.598 1.614-4.933 4.03-5.856a6.223 6.223 0 0 1 5.862-8.131h3.87z"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-gray-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    @endif
                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Informações Básicas --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome da Conta</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $emailAccount->name) }}" required class="input">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Endereço de Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $emailAccount->email) }}" required class="input">
                    </div>
                </div>

                {{-- Senha --}}
                <div>
                    <label for="smtp_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nova Senha de App
                        <span class="text-gray-400 font-normal">(deixe em branco para manter a atual)</span>
                    </label>
                    <input type="password" name="smtp_password" id="smtp_password" placeholder="••••••••••••••••" class="input">
                    <input type="hidden" name="imap_password" id="imap_password">
                </div>

                {{-- Configurações Avançadas --}}
                <div>
                    <button type="button" @click="showAdvanced = !showAdvanced" class="flex items-center gap-2 text-sm text-primary-600 dark:text-primary-400 hover:underline">
                        <svg class="w-4 h-4 transition-transform" :class="showAdvanced ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        Configurações Avançadas
                    </button>

                    <div x-show="showAdvanced" x-collapse class="mt-4 space-y-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        {{-- SMTP --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Configurações SMTP (Envio)</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Servidor SMTP</label>
                                    <input type="text" name="smtp_host" value="{{ old('smtp_host', $emailAccount->smtp_host) }}" class="input text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Porta SMTP</label>
                                    <input type="number" name="smtp_port" value="{{ old('smtp_port', $emailAccount->smtp_port) }}" class="input text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Usuário SMTP</label>
                                    <input type="text" name="smtp_username" value="{{ old('smtp_username', $emailAccount->smtp_username) }}" class="input text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Criptografia</label>
                                    <select name="smtp_encryption" class="input text-sm">
                                        <option value="tls" {{ $emailAccount->smtp_encryption === 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ $emailAccount->smtp_encryption === 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="none" {{ $emailAccount->smtp_encryption === 'none' ? 'selected' : '' }}>Nenhuma</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- IMAP --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Configurações IMAP (Recebimento)</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Servidor IMAP</label>
                                    <input type="text" name="imap_host" value="{{ old('imap_host', $emailAccount->imap_host) }}" class="input text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Porta IMAP</label>
                                    <input type="number" name="imap_port" value="{{ old('imap_port', $emailAccount->imap_port) }}" class="input text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Usuário IMAP</label>
                                    <input type="text" name="imap_username" value="{{ old('imap_username', $emailAccount->imap_username) }}" class="input text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Criptografia</label>
                                    <select name="imap_encryption" class="input text-sm">
                                        <option value="ssl" {{ $emailAccount->imap_encryption === 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="tls" {{ $emailAccount->imap_encryption === 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="none" {{ $emailAccount->imap_encryption === 'none' ? 'selected' : '' }}>Nenhuma</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="flex items-center gap-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ $emailAccount->is_active ? 'checked' : '' }} class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Conta ativa</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_default" value="1" {{ $emailAccount->is_default ? 'checked' : '' }} class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Conta padrão</span>
                    </label>
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <a href="{{ route('configuracoes.emails.index') }}" class="btn-secondary">Cancelar</a>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('smtp_password').addEventListener('input', function() {
            document.getElementById('imap_password').value = this.value;
        });
    </script>
</x-app-layout>
