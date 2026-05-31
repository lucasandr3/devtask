<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Editar Nota Fiscal</h2>
    </x-slot>

    <x-ui.page-back :fallback="route('notas-fiscais.index')" class="mb-6" />

    <div class="card p-6 w-full">
            <form method="POST" action="{{ route('notas-fiscais.update', $invoice) }}" enctype="multipart/form-data" class="space-y-6" id="invoice-form">
                @csrf
                @method('PUT')

                @include('invoices._xml-import')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="numero" value="Número da Nota Fiscal" />
                        <x-text-input type="text" name="numero" id="numero" value="{{ old('numero', $invoice->numero) }}" required class="mt-1" placeholder="Ex: 000001" />
                        <x-input-error :messages="$errors->get('numero')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="serie" value="Série (opcional)" />
                        <x-text-input type="text" name="serie" id="serie" value="{{ old('serie', $invoice->serie) }}" class="mt-1" placeholder="Ex: 1" />
                        <x-input-error :messages="$errors->get('serie')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="data_emissao" value="Data de Emissão" />
                        <x-text-input type="text" name="data_emissao" id="data_emissao" value="{{ old('data_emissao', $invoice->data_emissao->format('Y-m-d')) }}" required class="mt-1" data-datepicker placeholder="Selecione a data" />
                        <x-input-error :messages="$errors->get('data_emissao')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="valor" value="Valor (R$)" />
                        <x-text-input type="text" name="valor" id="valor" value="{{ old('valor', $invoice->valor) }}" required class="mt-1" data-money placeholder="R$ 0,00" />
                        <x-input-error :messages="$errors->get('valor')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="descricao" value="Descrição / Observações" />
                    <textarea name="descricao" id="descricao" rows="3" class="input mt-1" placeholder="Informações adicionais sobre a nota fiscal...">{{ old('descricao', $invoice->descricao) }}</textarea>
                    <x-input-error :messages="$errors->get('descricao')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <x-input-label for="client_id" value="Cliente" />
                        <select name="client_id" id="client_id" class="input mt-1">
                            <option value="">—</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" data-document="{{ preg_replace('/\D/', '', $client->document ?? '') }}" @selected(old('client_id', $invoice->client_id) == $client->id)>{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="project_id" value="Projeto" />
                        <select name="project_id" id="project_id" class="input mt-1">
                            <option value="">—</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" @selected(old('project_id', $invoice->project_id) == $project->id)>{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="payment_status" value="Recebimento" />
                        <select name="payment_status" id="payment_status" class="input mt-1">
                            @foreach(\App\Enums\InvoicePaymentStatus::cases() as $status)
                                <option value="{{ $status->value }}" @selected(old('payment_status', $invoice->payment_status?->value ?? 'received') === $status->value)>{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tributação --}}
                <div class="pt-4 border-t border-border">
                    <h3 class="text-lg font-semibold mb-1 text-foreground">Tributação (opcional)</h3>
                    <p class="text-sm text-muted-foreground mb-4">ISS, retenções e classificação do documento fiscal</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="invoice_type" value="Tipo de Nota" />
                            <select name="invoice_type" id="invoice_type" class="input mt-1">
                                <option value="service" {{ old('invoice_type', $invoice->invoice_type?->value ?? 'service') === 'service' ? 'selected' : '' }}>Serviço</option>
                                <option value="product" {{ old('invoice_type', $invoice->invoice_type?->value ?? 'service') === 'product' ? 'selected' : '' }}>Produto</option>
                            </select>
                            <x-input-error :messages="$errors->get('invoice_type')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="service_code" value="Código de Serviço (opcional)" />
                            <x-text-input type="text" name="service_code" id="service_code" value="{{ old('service_code', $invoice->service_code) }}" class="mt-1" placeholder="Ex: 1.01" />
                            <x-input-error :messages="$errors->get('service_code')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <x-input-label for="iss_value" value="Valor do ISS (R$)" />
                            <x-text-input type="text" name="iss_value" id="iss_value" value="{{ old('iss_value', $invoice->iss_value) }}" class="mt-1" data-money placeholder="R$ 0,00" />
                            <x-input-error :messages="$errors->get('iss_value')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tax_amount" value="Valor Total de Impostos (R$)" />
                            <x-text-input type="text" name="tax_amount" id="tax_amount" value="{{ old('tax_amount', $invoice->tax_amount) }}" class="mt-1" data-money placeholder="R$ 0,00" />
                            <x-input-error :messages="$errors->get('tax_amount')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div>
                    <x-input-label for="arquivo" value="Arquivo PDF (opcional)" />
                    @if($invoice->arquivo)
                        <div class="mt-2 mb-3 p-3 bg-muted/50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm text-foreground">Arquivo atual: {{ basename($invoice->arquivo) }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('notas-fiscais.visualizar', $invoice) }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 text-sm">
                                        Visualizar
                                    </a>
                                    <a href="{{ route('notas-fiscais.download', $invoice) }}" class="text-primary hover:text-primary-800 text-sm">
                                        Baixar
                                    </a>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-muted-foreground mb-2">Envie um novo arquivo para substituir o atual</p>
                    @endif
                    <x-text-input type="file" name="arquivo" id="arquivo" accept=".pdf" class="mt-1" />
                    <p class="mt-1 text-sm text-muted-foreground">Apenas arquivos PDF. Tamanho máximo: 10MB</p>
                    <x-input-error :messages="$errors->get('arquivo')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-border">
                    <a href="{{ back_url(route('notas-fiscais.index')) }}" class="btn-secondary">Cancelar</a>
                    <x-primary-button>Salvar</x-primary-button>
                </div>
            </form>
    </div>
</x-app-layout>
