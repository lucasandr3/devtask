<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Bem-vindo de volta</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-2">Entre com suas credenciais para acessar</p>
    </div>

    <form method="POST" action="{{ route('entrar') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="seu@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Senha" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="********" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 dark:border-slate-600 dark:bg-slate-700 text-primary-600 shadow-sm focus:ring-primary-500 dark:focus:ring-primary-400" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Lembrar-me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-medium" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <x-primary-button class="w-full justify-center py-3">
            Entrar
        </x-primary-button>

        @if (Route::has('register'))
            <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                Nao tem uma conta?
                <a href="{{ route('registrar') }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-medium">
                    Registre-se
                </a>
            </p>
        @endif
    </form>
</x-guest-layout>
