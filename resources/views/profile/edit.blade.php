<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Minha Conta</h2>
    </x-slot>

    <div class="space-y-6">
        <div class="card p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="card p-6">
            @include('profile.partials.update-password-form')
        </div>

        <div class="card p-6">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
