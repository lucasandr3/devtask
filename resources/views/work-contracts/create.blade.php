<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('contratos.index') }}" class="p-2 bg-gray-100 dark:bg-slate-700 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="page-title">Novo Contrato</h2>
        </div>
    </x-slot>

    <div>
        <div class="card p-6">
            <form method="POST" action="{{ route('contratos.store') }}" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="company_name" value="Nome da Empresa" />
                    <x-text-input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" class="mt-1" placeholder="Nome da empresa que está prestando serviço" />
                    <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="contract_value" value="Valor do Contrato (R$)" />
                    <x-text-input type="number" name="contract_value" id="contract_value" value="{{ old('contract_value') }}" step="0.01" min="0" class="mt-1" placeholder="0.00" />
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ex: 5000.00</p>
                    <x-input-error :messages="$errors->get('contract_value')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="monthly_hours" value="Horas Mensais" />
                    <x-text-input type="number" name="monthly_hours" id="monthly_hours" value="{{ old('monthly_hours') }}" step="0.01" min="0.01" required class="mt-1" placeholder="220" />
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Digite a quantidade de horas mensais (ex: 220 ou 220.5)</p>
                    <x-input-error :messages="$errors->get('monthly_hours')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="start_date" value="Data de Inicio" />
                        <x-text-input type="text" name="start_date" id="start_date" value="{{ old('start_date') }}" required class="mt-1" data-datepicker placeholder="Selecione a data" />
                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="end_date" value="Data de Fim (opcional)" />
                        <x-text-input type="text" name="end_date" id="end_date" value="{{ old('end_date') }}" class="mt-1" data-datepicker placeholder="Selecione a data" />
                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="notes" value="Observacoes" />
                    <textarea name="notes" id="notes" rows="3" class="input mt-1" placeholder="Informacoes adicionais sobre o contrato...">{{ old('notes') }}</textarea>
                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-slate-700">
                    <a href="{{ route('contratos.index') }}" class="btn-secondary">Cancelar</a>
                    <x-primary-button>Salvar</x-primary-button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
