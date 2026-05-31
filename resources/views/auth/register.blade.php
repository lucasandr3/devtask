<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-semibold tracking-tight">Criar conta</h1>
        <p class="text-muted-foreground mt-2 text-sm">Preencha os dados para se registrar</p>
    </div>

    <form method="POST" action="{{ route('registrar') }}" class="space-y-4">
        @csrf

        <div class="space-y-2">
            <x-input-label for="name" value="Nome" />
            <x-text-input id="name" class="block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Seu nome completo" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="seu@email.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="space-y-2">
            <x-input-label for="password" value="Senha" />
            <x-text-input id="password" class="block w-full" type="password" name="password" required autocomplete="new-password" placeholder="********" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="space-y-2">
            <x-input-label for="password_confirmation" value="Confirmar Senha" />
            <x-text-input id="password_confirmation" class="block w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="********" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <x-primary-button class="w-full">
            Registrar
        </x-primary-button>

        <p class="text-center text-sm text-muted-foreground">
            Ja tem uma conta?
            <a href="{{ route('entrar') }}" class="link-primary">
                Entrar
            </a>
        </p>
    </form>
</x-guest-layout>
