<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-6">
        <h1 class="text-2xl font-semibold tracking-tight">Bem-vindo de volta</h1>
        <p class="text-muted-foreground mt-2 text-sm">Entre com suas credenciais para acessar</p>
    </div>

    <form method="POST" action="{{ route('entrar') }}" class="space-y-4" data-login-form>
        @csrf

        <div class="space-y-2">
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="seu@email.com" />
        </div>

        <div class="space-y-2">
            <x-input-label for="password" value="Senha" />
            <x-text-input id="password" class="block w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="********" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2">
                <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border border-primary text-primary focus:ring-ring" name="remember">
                <span class="text-sm text-muted-foreground">Lembrar-me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-primary hover:underline font-medium" href="{{ route('password.request') }}">
                    Esqueceu a senha?
                </a>
            @endif
        </div>

        <x-primary-button class="w-full">
            Entrar
        </x-primary-button>

        @if (Route::has('register'))
            <p class="text-center text-sm text-muted-foreground">
                Nao tem uma conta?
                <a href="{{ route('registrar') }}" class="text-primary hover:underline font-medium">
                    Registre-se
                </a>
            </p>
        @endif
    </form>
</x-guest-layout>
