<x-app-layout>
    <x-slot name="header"><h2 class="page-title">Editar Cliente</h2></x-slot>
    <x-ui.page-back :fallback="route('clientes.index')" class="mb-6" />
    <div class="card p-6 w-full">
        <form method="POST" action="{{ route('clientes.update', $client) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('clients._form', ['client' => $client])
            <div class="flex justify-end gap-3 pt-4 border-t border-border">
                <a href="{{ back_url(route('clientes.index')) }}" class="btn-secondary">Cancelar</a>
                <x-primary-button>Salvar</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
