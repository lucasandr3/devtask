<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Novo Email</h2>
    </x-slot>

    <x-ui.page-back :href="route('emails.index')" class="mb-6" />

    <div class="card">
        <form action="{{ route('emails.store') }}" method="POST" enctype="multipart/form-data" x-data="emailForm()" x-ref="form">
            @csrf
            
            <div class="p-6 space-y-4">
                {{-- De (Conta) --}}
                <div class="flex items-center gap-4 pb-4 border-b border-border border-border">
                    <label class="w-16 text-sm font-medium text-muted-foreground">De:</label>
                    <select name="email_account_id" class="input flex-1" required>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ $account->is_default ? 'selected' : '' }}>
                                {{ $account->name }} ({{ $account->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Para --}}
                <div class="flex items-center gap-4">
                    <label class="w-16 text-sm font-medium text-muted-foreground">Para:</label>
                    <div class="flex-1 flex items-center gap-2">
                        <input 
                            type="text" 
                            name="to_emails" 
                            class="input flex-1" 
                            placeholder="destinatario@email.com (separe múltiplos com vírgula)"
                            required
                        >
                        <button type="button" @click="showCc = !showCc" class="text-sm text-primary hover:underline">Cc</button>
                        <button type="button" @click="showBcc = !showBcc" class="text-sm text-primary hover:underline">Cco</button>
                    </div>
                </div>

                {{-- Cc --}}
                <div x-show="showCc" x-collapse class="flex items-center gap-4">
                    <label class="w-16 text-sm font-medium text-muted-foreground">Cc:</label>
                    <input 
                        type="text" 
                        name="cc_emails" 
                        class="input flex-1" 
                        placeholder="copia@email.com"
                    >
                </div>

                {{-- Bcc --}}
                <div x-show="showBcc" x-collapse class="flex items-center gap-4">
                    <label class="w-16 text-sm font-medium text-muted-foreground">Cco:</label>
                    <input 
                        type="text" 
                        name="bcc_emails" 
                        class="input flex-1" 
                        placeholder="copiaoculta@email.com"
                    >
                </div>

                {{-- Assunto --}}
                <div class="flex items-center gap-4 pt-4 border-t border-border border-border">
                    <label class="w-16 text-sm font-medium text-muted-foreground">Assunto:</label>
                    <input 
                        type="text" 
                        name="subject" 
                        class="input flex-1" 
                        placeholder="Assunto do email"
                        required
                    >
                </div>

                {{-- Corpo do Email --}}
                <div class="pt-4 border-t border-border border-border">
                    <textarea 
                        name="body_html" 
                        rows="15" 
                        class="input w-full resize-none"
                        placeholder="Escreva sua mensagem aqui..."
                        required
                    ></textarea>
                </div>
            </div>

            {{-- Anexos --}}
            <div class="px-6 pb-4">
                {{-- Input de arquivo oculto --}}
                <input 
                    type="file" 
                    x-ref="fileInput"
                    class="hidden" 
                    multiple
                    @change="handleFileSelect($event)"
                >
                
                {{-- Container para os inputs de arquivo reais --}}
                <div x-ref="fileInputsContainer"></div>
                
                {{-- Lista de anexos --}}
                <div x-show="attachments.length > 0" class="mt-4 p-4 bg-muted/50 bg-card rounded-lg">
                    <p class="text-sm font-medium text-foreground mb-2">Anexos:</p>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="(file, index) in attachments" :key="file.id">
                            <div class="flex items-center gap-2 bg-white dark:bg-gray-700 px-3 py-2 rounded-lg border border-border dark:border-gray-600">
                                <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                <span class="text-sm text-foreground" x-text="file.name"></span>
                                <span class="text-xs text-muted-foreground" x-text="'(' + file.size + ')'"></span>
                                <button 
                                    type="button" 
                                    @click="removeFile(file.id)" 
                                    class="text-red-500 hover:text-red-700"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Ações --}}
            <div class="flex items-center justify-between p-6 border-t border-border border-border bg-muted/50 bg-card/50">
                <div class="flex items-center gap-2">
                    <button type="button" @click="$refs.fileInput.click()" class="p-2 rounded-lg text-muted-foreground hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors cursor-pointer ui-tooltip ui-tooltip-top" data-tooltip="Anexar arquivo" aria-label="Anexar arquivo">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                        </svg>
                    </button>
                    <span class="text-sm text-muted-foreground" x-show="attachments.length > 0" x-text="attachments.length + ' arquivo(s)'"></span>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" name="is_draft" value="1" class="btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Salvar Rascunho
                    </button>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Enviar
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function emailForm() {
            return {
                showCc: false,
                showBcc: false,
                attachments: [],
                fileCounter: 0,

                handleFileSelect(event) {
                    const files = event.target.files;
                    const container = this.$refs.fileInputsContainer;

                    Array.from(files).forEach(file => {
                        const id = ++this.fileCounter;
                        
                        // Criar um novo input file para cada arquivo
                        const input = document.createElement('input');
                        input.type = 'file';
                        input.name = 'attachments[]';
                        input.className = 'hidden';
                        input.dataset.fileId = id;
                        
                        // Usar DataTransfer para transferir o arquivo para o novo input
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        input.files = dataTransfer.files;
                        
                        container.appendChild(input);

                        // Adicionar à lista visual
                        this.attachments.push({
                            id: id,
                            name: file.name,
                            size: this.formatFileSize(file.size)
                        });
                    });

                    // Limpar o input original para permitir selecionar o mesmo arquivo novamente
                    event.target.value = '';
                },

                removeFile(id) {
                    // Remover o input file correspondente
                    const input = this.$refs.fileInputsContainer.querySelector(`input[data-file-id="${id}"]`);
                    if (input) {
                        input.remove();
                    }

                    // Remover da lista visual
                    this.attachments = this.attachments.filter(f => f.id !== id);
                },

                formatFileSize(bytes) {
                    if (bytes < 1024) return bytes + ' B';
                    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(2) + ' KB';
                    return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
                }
            }
        }
    </script>
</x-app-layout>
