<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Contatos do site</h2>
    </x-slot>

    <x-ui.page-back :href="route('painel')" class="mb-6" />

    <div class="flex flex-wrap items-center gap-2 mb-4">
        <a href="{{ route('contatos-site.index') }}"
           class="inline-flex items-center rounded-md px-3 py-1.5 text-sm transition-colors {{ ! $status ? 'bg-primary/10 text-primary font-medium' : 'hover:bg-muted' }}">
            Todos
        </a>
        <a href="{{ route('contatos-site.index', ['status' => 'new']) }}"
           class="inline-flex items-center rounded-md px-3 py-1.5 text-sm transition-colors {{ $status === 'new' ? 'bg-primary/10 text-primary font-medium' : 'hover:bg-muted' }}">
            Novos
            @if($newCount > 0)
                <span class="ml-1.5 inline-flex items-center justify-center rounded-full bg-primary text-primary-foreground text-xs min-w-[1.25rem] h-5 px-1">{{ $newCount }}</span>
            @endif
        </a>
        <a href="{{ route('contatos-site.index', ['status' => 'read']) }}"
           class="inline-flex items-center rounded-md px-3 py-1.5 text-sm transition-colors {{ $status === 'read' ? 'bg-primary/10 text-primary font-medium' : 'hover:bg-muted' }}">
            Lidos
        </a>
        <a href="{{ route('contatos-site.index', ['status' => 'archived']) }}"
           class="inline-flex items-center rounded-md px-3 py-1.5 text-sm transition-colors {{ $status === 'archived' ? 'bg-primary/10 text-primary font-medium' : 'hover:bg-muted' }}">
            Arquivados
        </a>
    </div>

    <x-data-table
        searchPlaceholder="Pesquisar contatos..."
        :selectable="false"
        tableId="siteLeadsTable"
    >
        <x-slot name="head">
            <x-data-table.header>Nome</x-data-table.header>
            <x-data-table.header>E-mail</x-data-table.header>
            <x-data-table.header>Empresa</x-data-table.header>
            <x-data-table.header>Segmento</x-data-table.header>
            <x-data-table.header>Status</x-data-table.header>
            <x-data-table.header>Recebido em</x-data-table.header>
            <x-data-table.header align="right">Ações</x-data-table.header>
        </x-slot>

        @forelse($leads as $lead)
            <x-data-table.row>
                <x-data-table.cell class="font-medium text-foreground">
                    <a href="{{ route('contatos-site.show', $lead) }}" class="hover:text-primary hover:underline">
                        {{ $lead->name }}
                    </a>
                </x-data-table.cell>
                <x-data-table.cell>{{ $lead->email }}</x-data-table.cell>
                <x-data-table.cell>{{ $lead->company_name ?? '-' }}</x-data-table.cell>
                <x-data-table.cell>{{ $lead->segment ?? '-' }}</x-data-table.cell>
                <x-data-table.cell>
                    <x-status-badge :status="$lead->status->label()" :color="$lead->status->badgeColor()" />
                </x-data-table.cell>
                <x-data-table.cell>{{ $lead->created_at->format('d/m/Y H:i') }}</x-data-table.cell>
                <x-data-table.cell align="right">
                    <x-data-table.actions
                        :viewRoute="route('contatos-site.show', $lead)"
                        :deleteRoute="route('contatos-site.destroy', $lead)"
                        deleteConfirm="Excluir este contato permanentemente?"
                    />
                </x-data-table.cell>
            </x-data-table.row>
        @empty
            <x-data-table.empty message="Nenhum contato recebido do site." :colspan="7" />
        @endforelse

        @if($leads->hasPages())
            <x-slot name="footer">{{ $leads->links() }}</x-slot>
        @endif
    </x-data-table>
</x-app-layout>
