<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Clientes</h2>
    </x-slot>

    <x-ui.page-back :href="route('financeiro.index')" class="mb-6" />

    <x-data-table
        searchPlaceholder="Pesquisar clientes..."
        :selectable="false"
        tableId="clientsTable"
        :createRoute="\App\Support\CurrentCompany::canManageFinance() ? route_with_return('clientes.create') : null"
    >
        <x-slot name="head">
            <x-data-table.header>Nome</x-data-table.header>
            <x-data-table.header>Documento</x-data-table.header>
            <x-data-table.header>Contato</x-data-table.header>
            <x-data-table.header>Projetos</x-data-table.header>
            @if(\App\Support\CurrentCompany::canManageFinance())
                <x-data-table.header align="right">Ações</x-data-table.header>
            @endif
        </x-slot>

        @forelse($clients as $client)
            <x-data-table.row>
                <x-data-table.cell class="font-medium text-foreground">{{ $client->name }}</x-data-table.cell>
                <x-data-table.cell>{{ $client->document ?? '-' }}</x-data-table.cell>
                <x-data-table.cell>{{ $client->email ?? $client->phone ?? '-' }}</x-data-table.cell>
                <x-data-table.cell>{{ $client->projects_count }}</x-data-table.cell>
                @if(\App\Support\CurrentCompany::canManageFinance())
                    <x-data-table.cell align="right">
                        <x-data-table.actions
                            :editRoute="route('clientes.edit', $client)"
                            :deleteRoute="route('clientes.destroy', $client)"
                        />
                    </x-data-table.cell>
                @endif
            </x-data-table.row>
        @empty
            <x-data-table.empty message="Nenhum cliente cadastrado." :colspan="5" />
        @endforelse

        @if($clients->hasPages())
            <x-slot name="footer">{{ $clients->links() }}</x-slot>
        @endif
    </x-data-table>
</x-app-layout>
