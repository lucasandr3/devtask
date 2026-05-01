<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="card p-6">
        <p class="text-gray-900 dark:text-gray-100">
            {{ __("You're logged in!") }}
        </p>
    </div>
</x-app-layout>
