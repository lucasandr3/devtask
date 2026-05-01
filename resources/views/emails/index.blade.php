<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Emails</h2>
    </x-slot>

    <div class="space-y-4">
        {{-- Barra de ações e navegação --}}
        <div class="card p-3">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                {{-- Botões de ação --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ route('emails.create') }}" class="btn-primary">
                        <svg class="w-4 h-4 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        <span class="hidden sm:inline">Escrever</span>
                    </a>
                    @if($accounts->isNotEmpty())
                        <form action="{{ route('emails.sync-all') }}" method="POST" x-data="{ syncing: false }" @submit="syncing = true">
                            @csrf
                            <button type="submit" class="btn-secondary px-3" title="Sincronizar emails" :disabled="syncing">
                                <svg class="w-4 h-4" :class="syncing ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Separador vertical --}}
                <div class="hidden sm:block w-px h-8 bg-gray-200 dark:bg-gray-700"></div>

                {{-- Tabs de navegação --}}
                <div class="flex-1 overflow-x-auto">
                    <div class="flex items-center gap-1 min-w-max">
                        <a href="{{ route('emails.index', ['folder' => 'inbox']) }}" 
                           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                                  {{ $folder === 'inbox' ? 'bg-primary-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <span>Entrada</span>
                            @if($counts['unread'] > 0)
                                <span class="px-1.5 py-0.5 text-xs font-semibold {{ $folder === 'inbox' ? 'bg-white/20 text-white' : 'bg-primary-500 text-white' }} rounded-full">{{ $counts['unread'] }}</span>
                            @endif
                        </a>

                        <a href="{{ route('emails.index', ['folder' => 'starred']) }}" 
                           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                                  {{ $folder === 'starred' ? 'bg-primary-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                            <span>Favoritos</span>
                            @if($counts['starred'] > 0)
                                <span class="text-xs {{ $folder === 'starred' ? 'text-white/70' : 'text-gray-500' }}">{{ $counts['starred'] }}</span>
                            @endif
                        </a>

                        <a href="{{ route('emails.index', ['folder' => 'sent']) }}" 
                           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                                  {{ $folder === 'sent' ? 'bg-primary-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <span>Enviados</span>
                            @if($counts['sent'] > 0)
                                <span class="text-xs {{ $folder === 'sent' ? 'text-white/70' : 'text-gray-500' }}">{{ $counts['sent'] }}</span>
                            @endif
                        </a>

                        <a href="{{ route('emails.index', ['folder' => 'drafts']) }}" 
                           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                                  {{ $folder === 'drafts' ? 'bg-primary-600 text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Rascunhos</span>
                            @if($counts['drafts'] > 0)
                                <span class="text-xs {{ $folder === 'drafts' ? 'text-white/70' : 'text-gray-500' }}">{{ $counts['drafts'] }}</span>
                            @endif
                        </a>

                        {{-- Separador --}}
                        <div class="w-px h-6 bg-gray-200 dark:bg-gray-700 mx-1"></div>

                        {{-- Configurações --}}
                        <a href="{{ route('configuracoes.emails.index') }}" 
                           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                           title="Gerenciar Contas">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="hidden md:inline">Configurações</span>
                        </a>
                    </div>
                </div>

                {{-- Filtro por Conta (dropdown) --}}
                @if($accounts->count() > 1)
                    <div class="hidden sm:block" x-data="{ open: false }">
                        <div class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors border border-gray-200 dark:border-gray-700">
                                <span class="w-2 h-2 rounded-full {{ $accountId ? 'bg-primary-500' : 'bg-gradient-to-r from-primary-500 to-purple-500' }}"></span>
                                <span class="truncate max-w-[120px]">{{ $accountId ? $accounts->firstWhere('id', $accountId)?->name : 'Todas' }}</span>
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                                <a href="{{ route('emails.index', ['folder' => $folder]) }}" 
                                   class="flex items-center gap-2 px-3 py-2 text-sm {{ !$accountId ? 'bg-gray-100 dark:bg-gray-700' : '' }} hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <span class="w-2 h-2 rounded-full bg-gradient-to-r from-primary-500 to-purple-500"></span>
                                    Todas as Contas
                                </a>
                                @foreach($accounts as $account)
                                    <a href="{{ route('emails.index', ['folder' => $folder, 'account' => $account->id]) }}" 
                                       class="flex items-center gap-2 px-3 py-2 text-sm {{ $accountId == $account->id ? 'bg-gray-100 dark:bg-gray-700' : '' }} hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <span class="w-2 h-2 rounded-full 
                                            @if($account->provider === 'gmail') bg-red-500
                                            @elseif($account->provider === 'outlook') bg-blue-500
                                            @elseif($account->provider === 'yahoo') bg-purple-500
                                            @else bg-gray-500
                                            @endif"></span>
                                        {{ $account->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Lista de Emails --}}
        <div class="card">
                @if($accounts->isEmpty())
                    {{-- Nenhuma conta configurada --}}
                    <div class="p-12 text-center">
                        <div class="mx-auto w-16 h-16 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Configure uma Conta de Email</h4>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">Você precisa adicionar uma conta de email para poder enviar e receber mensagens.</p>
                        <a href="{{ route('configuracoes.emails.index') }}" class="btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Configurar Conta
                        </a>
                    </div>
                @elseif($messages->isEmpty())
                    {{-- Nenhum email --}}
                    <div class="p-12 text-center">
                        <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                            @if($folder === 'inbox') Caixa de entrada vazia
                            @elseif($folder === 'sent') Nenhum email enviado
                            @elseif($folder === 'starred') Nenhum favorito
                            @elseif($folder === 'drafts') Nenhum rascunho
                            @endif
                        </h4>
                        <p class="text-gray-500 dark:text-gray-400">
                            @if($folder === 'inbox') Seus novos emails aparecerão aqui
                            @elseif($folder === 'sent') Os emails que você enviar aparecerão aqui
                            @elseif($folder === 'starred') Marque emails como favoritos para vê-los aqui
                            @elseif($folder === 'drafts') Rascunhos salvos aparecerão aqui
                            @endif
                        </p>
                    </div>
                @else
                    {{-- Lista de Emails --}}
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($messages as $message)
                            <div class="flex items-start gap-4 p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors {{ !$message->is_read ? 'bg-primary-50/50 dark:bg-primary-900/10' : '' }}">
                                {{-- Ações --}}
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <form action="{{ route('emails.toggle-star', $message) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                                            @if($message->is_starred)
                                                <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-gray-400 hover:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                </div>

                                {{-- Conteúdo --}}
                                <a href="{{ route('emails.show', $message) }}" class="flex-1 min-w-0 block">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-medium text-gray-900 dark:text-white truncate {{ !$message->is_read ? 'font-semibold' : '' }}">
                                            {{ $message->is_sent ? 'Para: ' . (is_array($message->to_emails) ? implode(', ', $message->to_emails) : $message->to_emails) : ($message->from_name ?? $message->from_email) }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 flex-shrink-0 ml-2">
                                            {{ ($message->received_at ?? $message->sent_at ?? $message->created_at)->format('d/m H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-900 dark:text-white truncate {{ !$message->is_read ? 'font-medium' : '' }}">
                                        {{ $message->subject }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                        {{ $message->preview }}
                                    </p>
                                </a>

                                {{-- Indicador de não lido --}}
                                @if(!$message->is_read)
                                    <div class="w-2 h-2 bg-primary-500 rounded-full flex-shrink-0 mt-2"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Paginação --}}
                    @if($messages->hasPages())
                        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $messages->appends(request()->query())->links() }}
                        </div>
                    @endif
                @endif
            </div>
    </div>
</x-app-layout>
