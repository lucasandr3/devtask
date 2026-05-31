<x-app-layout>

    <x-slot name="header">
        <h2 class="page-title">Nova Nota Fiscal</h2>
    </x-slot>

    <x-ui.page-back :href="route('notas-fiscais.index')" class="mb-6" />

    <div>
        <div class="card p-6">
            <form method="POST" action="{{ route('notas-fiscais.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

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
                        <x-text-input type="text" name="valor" id="valor" value="{{ old('valor') }}" required class="mt-1" data-money placeholder="0,00" />
                        <x-input-error :messages="$errors->get('valor')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="descricao" value="Descrição / Observações" />
                    <textarea name="descricao" id="descricao" rows="3" class="input mt-1" placeholder="Informações adicionais sobre a nota fiscal...">{{ old('descricao') }}</textarea>
                    <x-input-error :messages="$errors->get('descricao')" class="mt-2" />
                </div>

                {{-- Campos MEI --}}
                <div class="pt-4 border-t border-border">
                    <h3 class="text-lg font-semibold mb-4 text-foreground">Informações de Impostos MEI</h3>
                    
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
                            <x-text-input type="text" name="iss_value" id="iss_value" value="{{ old('iss_value') }}" class="mt-1" data-money placeholder="0,00" />
                            <x-input-error :messages="$errors->get('iss_value')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tax_amount" value="Valor Total de Impostos (R$)" />
                            <x-text-input type="text" name="tax_amount" id="tax_amount" value="{{ old('tax_amount') }}" class="mt-1" data-money placeholder="0,00" />
                            <x-input-error :messages="$errors->get('tax_amount')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div>
                    <x-input-label for="arquivo" value="Arquivo PDF (opcional)" />
                    <x-text-input type="file" name="arquivo" id="arquivo" accept=".pdf" class="mt-1" />
                    <p class="mt-1 text-sm text-muted-foreground">Apenas arquivos PDF. Tamanho máximo: 10MB</p>
                    <x-input-error :messages="$errors->get('arquivo')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-border">
                    <a href="{{ route('notas-fiscais.index') }}" class="btn-secondary">Cancelar</a>
                    <x-primary-button>Salvar</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
