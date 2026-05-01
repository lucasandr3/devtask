<section>
    <header>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            Informações do Perfil
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Atualize as informações do seu perfil e endereço de e-mail.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verificacao.enviar') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('perfil.atualizar') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nome" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        Seu endereço de e-mail não foi verificado.

                        <button form="send-verification" class="underline text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Clique aqui para reenviar o e-mail de verificação.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            Um novo link de verificação foi enviado para seu e-mail.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
            <h3 class="text-md font-medium text-gray-900 dark:text-white mb-4">Dados da Empresa (para relatórios)</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="company_name" value="Razão Social / Nome Fantasia" />
                    <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" :value="old('company_name', $user->company_name)" placeholder="Nome da sua empresa" />
                    <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
                </div>

                <div>
                    <x-input-label for="cnpj" value="CNPJ" />
                    <x-text-input id="cnpj" name="cnpj" type="text" class="mt-1 block w-full" :value="old('cnpj', $user->cnpj)" placeholder="00.000.000/0000-00" data-mask="cnpj" />
                    <x-input-error class="mt-2" :messages="$errors->get('cnpj')" />
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Salvar</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 dark:text-green-400"
                >Salvo.</p>
            @endif
        </div>
    </form>
</section>
