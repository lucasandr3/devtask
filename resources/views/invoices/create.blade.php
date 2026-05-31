<x-app-layout>

    <x-slot name="header">
        <h2 class="page-title">Nova Nota Fiscal</h2>
    </x-slot>

    <x-ui.page-back :fallback="route('notas-fiscais.index')" class="mb-6" />

    <div class="card p-6 w-full">
            <form method="POST" action="{{ route('notas-fiscais.store') }}" enctype="multipart/form-data" class="space-y-6" id="invoice-form">
                @csrf

                @include('invoices._xml-import')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="numero" value="Número da Nota Fiscal" />
                        <x-text-input type="text" name="numero" id="numero" value="{{ old('numero') }}" required class="mt-1" placeholder="Ex: 000001" />
                        <x-input-error :messages="$errors->get('numero')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="serie" value="Série (opcional)" />
                        <x-text-input type="text" name="serie" id="serie" value="{{ old('serie', '1') }}" class="mt-1" placeholder="Ex: 1" />
                        <x-input-error :messages="$errors->get('serie')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="data_emissao" value="Data de Emissão" />
                        <x-text-input type="text" name="data_emissao" id="data_emissao" value="{{ old('data_emissao') }}" required class="mt-1" data-datepicker placeholder="Selecione a data" />
                        <x-input-error :messages="$errors->get('data_emissao')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="valor" value="Valor (R$)" />
                        <x-text-input type="text" name="valor" id="valor" value="{{ old('valor') }}" required class="mt-1" data-money placeholder="R$ 0,00" />
                        <x-input-error :messages="$errors->get('valor')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="descricao" value="Descrição / Observações" />
                    <textarea name="descricao" id="descricao" rows="3" class="input mt-1" placeholder="Informações adicionais sobre a nota fiscal...">{{ old('descricao') }}</textarea>
                    <x-input-error :messages="$errors->get('descricao')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <x-input-label for="client_id" value="Cliente" />
                        <select name="client_id" id="client_id" class="input mt-1">
                            <option value="">—</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" data-document="{{ preg_replace('/\D/', '', $client->document ?? '') }}" @selected(old('client_id') == $client->id)>{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="project_id" value="Projeto" />
                        <select name="project_id" id="project_id" class="input mt-1">
                            <option value="">—</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="payment_status" value="Recebimento" />
                        <select name="payment_status" id="payment_status" class="input mt-1">
                            @foreach(\App\Enums\InvoicePaymentStatus::cases() as $status)
                                <option value="{{ $status->value }}" @selected(old('payment_status', 'received') === $status->value)>{{ $status->label() }}</option>
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
                                <option value="service" {{ old('invoice_type', 'service') === 'service' ? 'selected' : '' }}>Serviço</option>
                                <option value="product" {{ old('invoice_type') === 'product' ? 'selected' : '' }}>Produto</option>
                            </select>
                            <x-input-error :messages="$errors->get('invoice_type')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="service_code" value="Código de Serviço (opcional)" />
                            <x-text-input type="text" name="service_code" id="service_code" value="{{ old('service_code') }}" class="mt-1" placeholder="Ex: 1.01" />
                            <x-input-error :messages="$errors->get('service_code')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <x-input-label for="iss_value" value="Valor do ISS (R$)" />
                            <x-text-input type="text" name="iss_value" id="iss_value" value="{{ old('iss_value') }}" class="mt-1" data-money placeholder="R$ 0,00" />
                            <x-input-error :messages="$errors->get('iss_value')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tax_amount" value="Valor Total de Impostos (R$)" />
                            <x-text-input type="text" name="tax_amount" id="tax_amount" value="{{ old('tax_amount') }}" class="mt-1" data-money placeholder="R$ 0,00" />
                            <x-input-error :messages="$errors->get('tax_amount')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div>
                    <x-input-label for="arquivo" value="Arquivo PDF (opcional)" />
                    <x-text-input type="file" name="arquivo" id="arquivo" accept=".pdf,application/pdf" class="mt-1" />
                    <p class="mt-1 text-sm text-muted-foreground">PDF da nota (opcional). Tamanho máximo: 10MB</p>
                    <x-input-error :messages="$errors->get('arquivo')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-border">
                    <a href="{{ back_url(route('notas-fiscais.index')) }}" class="btn-secondary">Cancelar</a>
                    <x-primary-button>Salvar</x-primary-button>
                </div>
            </form>
    </div>
</x-app-layout>
