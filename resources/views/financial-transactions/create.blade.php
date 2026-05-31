<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">{{ ($type ?? 'payable') === 'receivable' ? 'Nova conta a receber' : 'Nova conta a pagar' }}</h2>
    </x-slot>
    <x-ui.page-back :fallback="route('financeiro.lancamentos.index')" class="mb-6" />
    <div class="card p-6 w-full">
        <form method="POST" action="{{ route('financeiro.lancamentos.store') }}" class="space-y-6">
            @csrf
            @include('financial-transactions._form')
            <div class="flex justify-end gap-3 pt-4 border-t border-border">
                <a href="{{ back_url(route('financeiro.lancamentos.index')) }}" class="btn-secondary">Cancelar</a>
                <x-primary-button>Salvar</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
