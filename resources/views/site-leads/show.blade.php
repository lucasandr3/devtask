<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title truncate">{{ $siteLead->name }}</h2>
    </x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <x-ui.page-back :href="route('contatos-site.index')" />

        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
            @if($siteLead->isConverted() && \App\Support\CurrentCompany::canViewFinance())
                <a href="{{ route('clientes.edit', $siteLead->client) }}" class="btn-primary btn-responsive">
                    <x-ui.icon name="clients" class="size-5" />
                    Ver cliente
                </a>
            @elseif(\App\Support\CurrentCompany::canManageFinance())
                <form action="{{ route('contatos-site.convert-client', $siteLead) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn-primary btn-responsive">
                        <x-ui.icon name="person-add" class="size-5" />
                        Converter em cliente
                    </button>
                </form>
            @endif

            @if($siteLead->status !== \App\Enums\SiteLeadStatus::ARCHIVED)
                <form action="{{ route('contatos-site.archive', $siteLead) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-secondary btn-responsive">
                        <x-ui.icon name="archive" class="size-5" />
                        Arquivar
                    </button>
                </form>
            @endif

            <form action="{{ route('contatos-site.destroy', $siteLead) }}" method="POST" class="inline"
                  data-confirm="Excluir este contato permanentemente?" data-confirm-title="Excluir contato?">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger btn-responsive">
                    <x-ui.icon name="delete" class="size-5" />
                    Excluir
                </button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="p-6 border-b border-border">
            <x-status-badge :status="$siteLead->status->label()" :color="$siteLead->status->badgeColor()" />
            <p class="mt-2 text-sm text-muted-foreground">
                Recebido em {{ $siteLead->created_at->format('d/m/Y \à\s H:i') }}
            </p>
            @if($siteLead->isConverted() && $siteLead->client)
                <p class="mt-2 text-sm">
                    <span class="text-muted-foreground">Cliente:</span>
                    <a href="{{ route('clientes.edit', $siteLead->client) }}" class="text-primary hover:underline font-medium">
                        {{ $siteLead->client->name }}
                    </a>
                </p>
            @endif
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
            <div>
                <dt class="text-sm font-medium text-muted-foreground">Segmento</dt>
                <dd class="mt-1 text-foreground">{{ $siteLead->segment_label }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-muted-foreground">Origem</dt>
                <dd class="mt-1 text-foreground">{{ $siteLead->source ?? '—' }}</dd>
            </div>
        </dl>

        @if($siteLead->privacy_consent)
            <div class="px-6 pb-6 border-t border-border pt-6">
                <h3 class="text-sm font-medium text-muted-foreground mb-3">Consentimento LGPD</h3>
                <dl class="grid gap-3 sm:grid-cols-2 text-sm">
                    <div>
                        <dt class="text-muted-foreground">Política aceita</dt>
                        <dd class="mt-0.5 text-foreground">
                            v{{ $siteLead->privacy_policy_version }}
                            @if(config('site-legal.legal.last_updated_label'))
                                <span class="text-muted-foreground">({{ config('site-legal.legal.last_updated_label') }})</span>
                            @endif
                        </dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-muted-foreground">Controlador</dt>
                        <dd class="mt-0.5 text-foreground">
                            {{ config('site-legal.legal.controller_name') }}
                            — {{ config('site-legal.legal.city') }}/{{ config('site-legal.legal.state') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-muted-foreground">Consentido em</dt>
                        <dd class="mt-0.5 text-foreground">
                            {{ $siteLead->privacy_consented_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') ?? '—' }}
                        </dd>
                    </div>
                </dl>
            </div>
        @endif

        <div class="px-6 pb-6">
            <h3 class="text-sm font-medium text-muted-foreground mb-2">Mensagem</h3>
            <div class="rounded-lg bg-muted/50 p-4 text-foreground whitespace-pre-wrap">{{ $siteLead->message }}</div>
        </div>

        @if($siteLead->ip_address || $siteLead->user_agent)
            <div class="px-6 pb-6 pt-2 border-t border-border text-xs text-muted-foreground space-y-1">
                @if($siteLead->ip_address)
                    <p>IP: {{ $siteLead->ip_address }}</p>
                @endif
                @if($siteLead->user_agent)
                    <p class="break-all">User-Agent: {{ $siteLead->user_agent }}</p>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
