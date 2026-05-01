<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('emails.index') }}" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h2 class="page-title truncate">{{ $emailMessage->subject }}</h2>
            </div>
            <div class="flex items-center gap-2">
                <form action="{{ route('emails.toggle-star', $emailMessage) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="Favoritar">
                        @if($emailMessage->is_starred)
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        @endif
                    </button>
                </form>

                <form action="{{ route('emails.destroy', $emailMessage) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este email?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-400 hover:text-red-500 transition-colors" title="Excluir">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="card">
        {{-- Header do Email --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-start gap-4">
                {{-- Avatar --}}
                <div class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center flex-shrink-0">
                    <span class="text-lg font-semibold text-primary-700 dark:text-primary-300">
                        {{ strtoupper(substr($emailMessage->from_name ?? $emailMessage->from_email, 0, 1)) }}
                    </span>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                {{ $emailMessage->from_name ?? $emailMessage->from_email }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $emailMessage->from_email }}
                            </p>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400 flex-shrink-0">
                            {{ ($emailMessage->received_at ?? $emailMessage->sent_at ?? $emailMessage->created_at)->format('d/m/Y H:i') }}
                        </span>
                    </div>

                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        <p>
                            <span class="text-gray-500">Para:</span> 
                            {{ is_array($emailMessage->to_emails) ? implode(', ', $emailMessage->to_emails) : $emailMessage->to_emails }}
                        </p>
                        @if($emailMessage->cc_emails && count($emailMessage->cc_emails) > 0)
                            <p>
                                <span class="text-gray-500">Cc:</span> 
                                {{ implode(', ', $emailMessage->cc_emails) }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Corpo do Email --}}
        <div class="p-6">
            <div class="prose dark:prose-invert max-w-none">
                @if($emailMessage->body_html)
                    {!! $emailMessage->body_html !!}
                @else
                    <pre class="whitespace-pre-wrap font-sans">{{ $emailMessage->body_text }}</pre>
                @endif
            </div>
        </div>

        {{-- Anexos --}}
        @if($emailMessage->attachments && count($emailMessage->attachments) > 0)
            <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                    Anexos ({{ count($emailMessage->attachments) }})
                </h4>
                <div class="flex flex-wrap gap-2">
                    @foreach($emailMessage->attachments as $attachment)
                        <div class="flex items-center gap-2 px-3 py-2 bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $attachment['name'] ?? 'Anexo' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Ações --}}
        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <a href="{{ route('emails.create') }}?reply_to={{ $emailMessage->id }}" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                    </svg>
                    Responder
                </a>
                <a href="{{ route('emails.create') }}?forward={{ $emailMessage->id }}" class="btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                    Encaminhar
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
