<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Novo DAS</h2>
    </x-slot>

    <x-ui.page-back :href="route('das.index')" class="mb-6" />

    <div>
        <div class="card p-6">
            <form method="POST" action="{{ route('das.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="reference_month" value="Mês de Referência" />
                        <x-text-input type="text" name="reference_month" id="reference_month" value="{{ old('reference_month') }}" required class="mt-1" data-monthpicker placeholder="Selecione o mês" />
                        <x-input-error :messages="$errors->get('reference_month')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="due_date" value="Data de Vencimento" />
                        <x-text-input type="text" name="due_date" id="due_date" value="{{ old('due_date') }}" required class="mt-1" data-datepicker placeholder="Selecione a data" />
                        <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="amount" value="Valor (R$)" />
                        <x-text-input type="text" name="amount" id="amount" value="{{ old('amount') }}" required class="mt-1" data-money placeholder="0,00" />
                        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="payment_date" value="Data de Pagamento (opcional)" />
                        <x-text-input type="text" name="payment_date" id="payment_date" value="{{ old('payment_date') }}" class="mt-1" data-datepicker placeholder="Selecione a data" />
                        <x-input-error :messages="$errors->get('payment_date')" class="mt-2" />
                        <p class="mt-1 text-sm text-muted-foreground">Deixe em branco se ainda não foi pago</p>
                    </div>
                </div>

                <div>
                    <x-input-label for="notes" value="Observações (opcional)" />
                    <textarea name="notes" id="notes" rows="3" class="input mt-1" placeholder="Informações adicionais sobre o DAS...">{{ old('notes') }}</textarea>
                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="receipt_file" value="Comprovante de Pagamento (opcional)" />
                    <x-text-input type="file" name="receipt_file" id="receipt_file" accept=".pdf,.jpg,.jpeg,.png" class="mt-1" />
                    <p class="mt-1 text-sm text-muted-foreground">PDF, JPG, JPEG ou PNG. Tamanho máximo: 10MB</p>
                    <x-input-error :messages="$errors->get('receipt_file')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-border">
                    <a href="{{ route('das.index') }}" class="btn-secondary">Cancelar</a>
                    <x-primary-button>Salvar</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
