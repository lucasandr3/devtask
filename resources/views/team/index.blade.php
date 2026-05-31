<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Equipe</h2>
    </x-slot>

    <x-data-table
        :createRoute="route('equipe.create')"
        createLabel="Novo Membro"
        searchPlaceholder="Pesquisar membros..."
        :selectable="false"
        tableId="equipeTable"
    >
        <x-slot name="head">
            <x-data-table.header class="w-[30%]">Nome</x-data-table.header>
            <x-data-table.header class="w-[35%]">E-mail</x-data-table.header>
            <x-data-table.header class="data-table-th-compact">Papel</x-data-table.header>
            <x-data-table.header align="right" class="data-table-th-actions">Ações</x-data-table.header>
        </x-slot>

        @forelse($members as $member)
            @php
                $role = \App\Enums\CompanyRole::tryFrom($member->pivot->role);
            @endphp
            <x-data-table.row>
                <x-data-table.cell truncate class="font-medium text-foreground">
                    {{ $member->name }}
                </x-data-table.cell>
                <x-data-table.cell truncate>{{ $member->email }}</x-data-table.cell>
                <x-data-table.cell class="data-table-td-compact">
                    <x-status-badge :status="$role?->label() ?? 'Membro'" color="blue" />
                </x-data-table.cell>
                <x-data-table.actions
                    :editRoute="route('equipe.edit', $member)"
                    :deleteRoute="$member->id !== auth()->id() ? route('equipe.destroy', $member) : null"
                    deleteConfirm="Remover este membro da equipe?"
                />
            </x-data-table.row>
        @empty
            <x-data-table.empty
                colspan="4"
                message="Nenhum membro cadastrado."
                :createRoute="route('equipe.create')"
                createLabel="Adicionar primeiro membro"
            />
        @endforelse
    </x-data-table>
</x-app-layout>
