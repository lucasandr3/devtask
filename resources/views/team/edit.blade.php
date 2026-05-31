<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Editar Membro</h2>
    </x-slot>

    <x-ui.page-back :href="route('equipe.index')" class="mb-6" />

    <div class="space-y-6 w-full">
        <div class="card p-6">
            <form method="POST" action="{{ route('equipe.update', $member) }}" class="space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <x-input-label for="name" value="Nome" />
                    <x-text-input type="text" name="name" id="name" value="{{ old('name', $member->name) }}" required class="mt-1" placeholder="Nome completo" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email" value="E-mail" />
                    <x-text-input type="email" name="email" id="email" value="{{ old('email', $member->email) }}" required class="mt-1" placeholder="email@exemplo.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" value="Nova senha" />
                    <x-text-input type="password" name="password" id="password" class="mt-1" placeholder="Deixe em branco para manter a atual" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="role" value="Papel" />
                    <select name="role" id="role" required class="input mt-1">
                        @foreach(\App\Enums\CompanyRole::cases() as $role)
                            <option value="{{ $role->value }}" {{ old('role', $member->pivot->role) === $role->value ? 'selected' : '' }}>
                                {{ $role->label() }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    @if($member->id === auth()->id())
                        <p class="text-xs text-muted-foreground mt-1">Você não pode remover seu próprio papel de administrador.</p>
                    @endif
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-border">
                    <a href="{{ route('equipe.index') }}" class="btn-secondary">Cancelar</a>
                    <x-primary-button>Salvar</x-primary-button>
                </div>
            </form>
        </div>

        @if($member->id !== auth()->id())
            <div class="card p-6 border-destructive/30">
                <h3 class="text-lg font-semibold text-destructive mb-2">Zona de perigo</h3>
                <p class="text-sm text-muted-foreground mb-4">Remove o membro da equipe sem excluir a conta do usuário.</p>
                <form method="POST" action="{{ route('equipe.destroy', $member) }}" data-confirm="Remover este membro da equipe?" data-confirm-title="Remover membro?">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger">Remover da equipe</button>
                </form>
            </div>
        @endif
    </div>
</x-app-layout>
