<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Novo Projeto</h2>
    </x-slot>

    <x-ui.page-back :href="route('projetos.index')" class="mb-6" />

    <div class="card p-6 w-full">
        <form method="POST" action="{{ route('projetos.store') }}" class="space-y-6">
            @csrf

            <div>
                <x-input-label for="name" value="Nome do Projeto" />
                <x-text-input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1" placeholder="Nome do projeto" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="description" value="Descrição" />
                <textarea name="description" id="description" rows="3" class="input mt-1" placeholder="Descreva o projeto...">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="status" value="Status" />
                <select name="status" id="status" required class="input mt-1">
                    @foreach(\App\Enums\ProjectStatus::cases() as $status)
                        <option value="{{ $status->value }}" {{ old('status', 'active') === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="starts_at" value="Início" />
                    <x-text-input type="text" name="starts_at" id="starts_at" value="{{ old('starts_at') }}" class="mt-1" data-datepicker placeholder="Opcional" />
                    <x-input-error :messages="$errors->get('starts_at')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="ends_at" value="Previsão de término" />
                    <x-text-input type="text" name="ends_at" id="ends_at" value="{{ old('ends_at') }}" class="mt-1" data-datepicker placeholder="Opcional" />
                    <x-input-error :messages="$errors->get('ends_at')" class="mt-2" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-border">
                <a href="{{ route('projetos.index') }}" class="btn-secondary">Cancelar</a>
                <x-primary-button>Salvar</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
