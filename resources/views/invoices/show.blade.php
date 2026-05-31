<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Nota Fiscal #{{ $invoice->numero }}</h2>
    </x-slot>

    <x-ui.page-back :href="route('notas-fiscais.index')" class="mb-6" />

    <div class="w-full">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Informações --}}
            <div class="lg:col-span-1">
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-foreground mb-4">Informações</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-muted-foreground">Número</p>
                            <p class="text-base font-medium text-foreground">{{ $invoice->numero }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Série</p>
                            <p class="text-base font-medium text-foreground">{{ $invoice->serie }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Data de Emissão</p>
                            <p class="text-base font-medium text-foreground">{{ $invoice->data_emissao->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Valor</p>
                            <p class="text-lg font-semibold text-primary">R$ {{ number_format($invoice->valor, 2, ',', '.') }}</p>
                        </div>
                        @if($invoice->descricao)
                            <div>
                                <p class="text-sm text-muted-foreground">Descrição</p>
                                <p class="text-base text-foreground">{{ $invoice->descricao }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="mt-6 pt-6 border-t border-border flex gap-3">
                        <a href="{{ route('notas-fiscais.edit', $invoice) }}" class="btn-secondary flex-1 text-center">
                            Editar
                        </a>
                        @if($invoice->arquivo)
                            <a href="{{ route('notas-fiscais.download', $invoice) }}" class="btn-primary flex-1 text-center">
                                Baixar PDF
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Visualização do PDF --}}
            <div class="lg:col-span-2">
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-foreground">Visualização</h3>
                        @if($invoice->arquivo)
                            <a href="{{ route('notas-fiscais.download', $invoice) }}" class="text-primary hover:text-primary/80 text-sm font-medium flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Baixar
                            </a>
                        @endif
                    </div>
                    @if($invoice->arquivo)
                        <div class="border border-border rounded-lg overflow-hidden bg-muted/50 bg-background">
                            <iframe 
                                src="{{ route('notas-fiscais.visualizar', $invoice) }}" 
                                class="w-full h-[600px]"
                                frameborder="0"
                            ></iframe>
                        </div>
                    @else
                        <div class="border border-border rounded-lg p-12 text-center">
                            <svg class="w-16 h-16 text-gray-300 dark:text-muted-foreground mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-muted-foreground mb-4">Nenhum arquivo PDF cadastrado</p>
                            <a href="{{ route('notas-fiscais.edit', $invoice) }}" class="btn-primary inline-block">
                                Adicionar Arquivo
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
