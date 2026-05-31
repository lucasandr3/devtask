<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Registrar Hora Manual</h2>
    </x-slot>

    <x-ui.page-back :href="route('horas.index')" class="mb-6" />

    <div>
        <div class="card p-6">
            <form method="POST" action="{{ route('horas.salvar') }}" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="work_date" value="Data do Trabalho" />
                    <x-text-input type="text" name="work_date" id="work_date" value="{{ old('work_date', \Carbon\Carbon::today()->format('Y-m-d')) }}" required class="mt-1" data-datepicker placeholder="Selecione a data" />
                    <p class="mt-1 text-sm text-muted-foreground">Selecione a data para registro retroativo</p>
                    <x-input-error :messages="$errors->get('work_date')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="entry_time" value="Entrada (HH:MM)" />
                        <x-text-input type="text" name="entry_time" id="entry_time" value="{{ old('entry_time') }}" class="mt-1" placeholder="08:00" maxlength="5" />
                        <x-input-error :messages="$errors->get('entry_time')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="lunch_out_time" value="Saída Almoço (HH:MM)" />
                        <x-text-input type="text" name="lunch_out_time" id="lunch_out_time" value="{{ old('lunch_out_time') }}" class="mt-1" placeholder="12:00" maxlength="5" />
                        <x-input-error :messages="$errors->get('lunch_out_time')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="lunch_return_time" value="Volta Almoço (HH:MM)" />
                        <x-text-input type="text" name="lunch_return_time" id="lunch_return_time" value="{{ old('lunch_return_time') }}" class="mt-1" placeholder="13:00" maxlength="5" />
                        <x-input-error :messages="$errors->get('lunch_return_time')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="exit_time" value="Saída (HH:MM)" />
                        <x-text-input type="text" name="exit_time" id="exit_time" value="{{ old('exit_time') }}" class="mt-1" placeholder="18:00" maxlength="5" />
                        <x-input-error :messages="$errors->get('exit_time')" class="mt-2" />
                    </div>
                </div>

                <div class="border-t border-border pt-6">
                    <h3 class="text-lg font-semibold text-foreground mb-4">Horas Extras (Opcional)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="extra_start_time" value="Início Hora Extra (HH:MM)" />
                            <x-text-input type="text" name="extra_start_time" id="extra_start_time" value="{{ old('extra_start_time') }}" class="mt-1" placeholder="18:30" maxlength="5" />
                            <x-input-error :messages="$errors->get('extra_start_time')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="extra_end_time" value="Fim Hora Extra (HH:MM)" />
                            <x-text-input type="text" name="extra_end_time" id="extra_end_time" value="{{ old('extra_end_time') }}" class="mt-1" placeholder="20:00" maxlength="5" />
                            <x-input-error :messages="$errors->get('extra_end_time')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div>
                    <x-input-label for="notes" value="Observações" />
                    <textarea name="notes" id="notes" rows="3" class="input mt-1" placeholder="Observações sobre a hora...">{{ old('notes') }}</textarea>
                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-border">
                    <a href="{{ route('horas.index') }}" class="btn-secondary">Cancelar</a>
                    <x-primary-button>Salvar</x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timeInputs = ['entry_time', 'lunch_out_time', 'lunch_return_time', 'exit_time', 'extra_start_time', 'extra_end_time'];
            
            timeInputs.forEach(inputId => {
                const input = document.getElementById(inputId);
                if (input) {
                    input.addEventListener('input', function(e) {
                        let value = e.target.value.replace(/\D/g, '');
                        if (value.length >= 2) {
                            value = value.substring(0, 2) + ':' + value.substring(2, 4);
                        }
                        e.target.value = value;
                    });
                }
            });
        });
    </script>
</x-app-layout>
