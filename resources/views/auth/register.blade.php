<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Criar conta</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-2">Preencha os dados para se registrar</p>
    </div>

    <form method="POST" action="{{ route('registrar') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nome" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Seu nome completo" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="seu@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Senha" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password"
                            placeholder="********" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Confirmar Senha" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="********" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center py-3">
            Registrar
        </x-primary-button>

        <p class="text-center text-sm text-gray-600 dark:text-gray-400">
            Ja tem uma conta?
            <a href="{{ route('entrar') }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-medium">
                Entrar
            </a>
        </p>
    </form>
</x-guest-layout>
