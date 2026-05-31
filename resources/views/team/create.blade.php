<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Novo Membro</h2>
    </x-slot>

    <x-ui.page-back :href="route('equipe.index')" class="mb-6" />

    <div class="card p-6 w-full">
        <form method="POST" action="{{ route('equipe.store') }}" class="space-y-6">
            @csrf

            <div>
                <x-input-label for="name" value="Nome" />
                <x-text-input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1" placeholder="Nome completo" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" value="E-mail" />
                <x-text-input type="email" name="email" id="email" value="{{ old('email') }}" required class="mt-1" placeholder="email@exemplo.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" value="Senha inicial" />
                <x-text-input type="password" name="password" id="password" required class="mt-1" placeholder="Mínimo 8 caracteres" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="role" value="Papel" />
                <select name="role" id="role" required class="input mt-1">
                    @foreach(\App\Enums\CompanyRole::cases() as $role)
                        <option value="{{ $role->value }}" {{ old('role', 'member') === $role->value ? 'selected' : '' }}>
                            {{ $role->label() }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-border">
                <a href="{{ route('equipe.index') }}" class="btn-secondary">Cancelar</a>
                <x-primary-button>Adicionar à equipe</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
