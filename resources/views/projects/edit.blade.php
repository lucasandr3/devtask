<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Editar Projeto</h2>
    </x-slot>

    <x-ui.page-back :href="route('projetos.show', $project)" class="mb-6" />

    <div class="space-y-6 w-full">
        <div class="card p-6">
            <form method="POST" action="{{ route('projetos.update', $project) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="name" value="Nome do Projeto" />
                    <x-text-input type="text" name="name" id="name" value="{{ old('name', $project->name) }}" required class="mt-1" placeholder="Nome do projeto" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" value="Descrição" />
                    <textarea name="description" id="description" rows="3" class="input mt-1" placeholder="Descreva o projeto...">{{ old('description', $project->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="status" value="Status" />
                    <select name="status" id="status" required class="input mt-1">
                        @foreach(\App\Enums\ProjectStatus::cases() as $status)
                            <option value="{{ $status->value }}" {{ old('status', $project->status->value) === $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                </div>

                @if($clients->isNotEmpty())
                    <div>
                        <x-input-label for="client_id" value="Cliente" />
                        <select name="client_id" id="client_id" class="input mt-1">
                            <option value="">—</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" @selected(old('client_id', $project->client_id) == $client->id)>{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if(\App\Support\CurrentCompany::canViewFinance())
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="budget" value="Orçamento (R$)" />
                            <x-text-input type="text" name="budget" id="budget" value="{{ old('budget', $project->budget) }}" class="mt-1" data-money placeholder="R$ 0,00" />
                        </div>
                        <div>
                            <x-input-label for="hourly_rate" value="Taxa horária cobrada (R$)" />
                            <x-text-input type="text" name="hourly_rate" id="hourly_rate" value="{{ old('hourly_rate', $project->hourly_rate) }}" class="mt-1" data-money placeholder="R$ 0,00" />
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="starts_at" value="Início" />
                        <x-text-input type="text" name="starts_at" id="starts_at" value="{{ old('starts_at', $project->starts_at?->format('Y-m-d')) }}" class="mt-1" data-datepicker placeholder="Selecione a data" />
                        <x-input-error :messages="$errors->get('starts_at')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="ends_at" value="Previsão de término" />
                        <x-text-input type="text" name="ends_at" id="ends_at" value="{{ old('ends_at', $project->ends_at?->format('Y-m-d')) }}" class="mt-1" data-datepicker placeholder="Selecione a data" />
                        <x-input-error :messages="$errors->get('ends_at')" class="mt-2" />
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-border">
                    <a href="{{ route('projetos.show', $project) }}" class="btn-secondary">Cancelar</a>
                    <x-primary-button>Salvar</x-primary-button>
                </div>
            </form>
        </div>

        <div class="card p-6 border-red-200 dark:border-red-900/50">
            <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-2">Zona de perigo</h3>
            <p class="text-sm text-muted-foreground mb-4">Excluir o projeto remove todas as tarefas vinculadas.</p>
            <form method="POST" action="{{ route('projetos.destroy', $project) }}" data-confirm="Excluir este projeto e todas as tarefas?" data-confirm-title="Excluir projeto?">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">Excluir Projeto</button>
            </form>
        </div>
    </div>
</x-app-layout>
