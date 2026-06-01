<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="page-title truncate">{{ $siteLead->name }}</h2>
            <div class="flex items-center gap-2 flex-shrink-0">
                @if($siteLead->status !== \App\Enums\SiteLeadStatus::ARCHIVED)
                    <form action="{{ route('contatos-site.archive', $siteLead) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-secondary text-sm">Arquivar</button>
                    </form>
                @endif
                <form action="{{ route('contatos-site.destroy', $siteLead) }}" method="POST" class="inline"
                      data-confirm="Excluir este contato permanentemente?" data-confirm-title="Excluir contato?">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger text-sm">Excluir</button>
                </form>
            </div>
        </div>
    </x-slot>

    <x-ui.page-back :href="route('contatos-site.index')" class="mb-6" />

    <div class="card">
        <div class="p-6 border-b border-border">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <x-status-badge :status="$siteLead->status->label()" :color="$siteLead->status->badgeColor()" />
                    <p class="mt-2 text-sm text-muted-foreground">
                        Recebido em {{ $siteLead->created_at->format('d/m/Y \à\s H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <dl class="p-6 grid gap-4 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-muted-foreground">Nome</dt>
                <dd class="mt-1 text-foreground">{{ $siteLead->name }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-muted-foreground">E-mail</dt>
                <dd class="mt-1">
                    <a href="mailto:{{ $siteLead->email }}" class="text-primary hover:underline">{{ $siteLead->email }}</a>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-muted-foreground">Empresa</dt>
                <dd class="mt-1 text-foreground">{{ $siteLead->company_name ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-muted-foreground">Telefone</dt>
                <dd class="mt-1 text-foreground">
                    @if($siteLead->phone)
                        <a href="tel:{{ $siteLead->phone }}" class="text-primary hover:underline">{{ $siteLead->phone }}</a>
                    @else
                        —
                    @endif
                </dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-muted-foreground">Segmento</dt>
                <dd class="mt-1 text-foreground">{{ $siteLead->segment ?? '—' }}</dd>
            </div>
        </dl>

        <div class="px-6 pb-6">
            <h3 class="text-sm font-medium text-muted-foreground mb-2">Mensagem</h3>
            <div class="rounded-lg bg-muted/50 p-4 text-foreground whitespace-pre-wrap">{{ $siteLead->message }}</div>
        </div>

        @if($siteLead->ip || $siteLead->user_agent)
            <div class="px-6 pb-6 pt-2 border-t border-border text-xs text-muted-foreground space-y-1">
                @if($siteLead->ip)
                    <p>IP: {{ $siteLead->ip }}</p>
                @endif
                @if($siteLead->user_agent)
                    <p class="break-all">User-Agent: {{ $siteLead->user_agent }}</p>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
