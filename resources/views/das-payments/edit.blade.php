@php
use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('das.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="page-title">Editar DAS</h2>
        </div>
    </x-slot>

    <div>
        <div class="card p-6">
            <form method="POST" action="{{ route('das.update', $dasPayment) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="reference_month" value="Mês de Referência" />
                        <x-text-input type="text" name="reference_month" id="reference_month" value="{{ old('reference_month', $dasPayment->reference_month->format('Y-m')) }}" required class="mt-1" data-monthpicker placeholder="Selecione o mês" />
                        <x-input-error :messages="$errors->get('reference_month')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="due_date" value="Data de Vencimento" />
                        <x-text-input type="text" name="due_date" id="due_date" value="{{ old('due_date', $dasPayment->due_date->format('Y-m-d')) }}" required class="mt-1" data-datepicker placeholder="Selecione a data" />
                        <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="amount" value="Valor (R$)" />
                        <x-text-input type="text" name="amount" id="amount" value="{{ old('amount', $dasPayment->amount) }}" required class="mt-1" data-money placeholder="0,00" />
                        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="payment_date" value="Data de Pagamento (opcional)" />
                        <x-text-input type="text" name="payment_date" id="payment_date" value="{{ old('payment_date', $dasPayment->payment_date?->format('Y-m-d')) }}" class="mt-1" data-datepicker placeholder="Selecione a data" />
                        <x-input-error :messages="$errors->get('payment_date')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Deixe em branco se ainda não foi pago</p>
                    </div>
                </div>

                <div>
                    <x-input-label for="notes" value="Observações (opcional)" />
                    <textarea name="notes" id="notes" rows="3" class="input mt-1" placeholder="Informações adicionais sobre o DAS...">{{ old('notes', $dasPayment->notes) }}</textarea>
                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="receipt_file" value="Comprovante de Pagamento (opcional)" />
                    @if($dasPayment->receipt_file)
                        <div class="mt-2 mb-3 p-3 bg-gray-50 dark:bg-slate-800 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Comprovante atual: {{ basename($dasPayment->receipt_file) }}</span>
                                </div>
                                <a href="{{ Storage::url('public/' . $dasPayment->receipt_file) }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 text-sm">
                                    Visualizar
                                </a>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Envie um novo arquivo para substituir o atual</p>
                    @endif
                    <x-text-input type="file" name="receipt_file" id="receipt_file" accept=".pdf,.jpg,.jpeg,.png" class="mt-1" />
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">PDF, JPG, JPEG ou PNG. Tamanho máximo: 10MB</p>
                    <x-input-error :messages="$errors->get('receipt_file')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-slate-700">
                    <a href="{{ route('das.index') }}" class="btn-secondary">Cancelar</a>
                    <x-primary-button>Salvar</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
