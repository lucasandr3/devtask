<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'GestorPro') }}</title>

        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        <link rel="apple-touch-icon" href="{{ asset('web-app-manifest-192x192.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=roboto:400,500,600,700&display=swap" rel="stylesheet" />

        <x-theme-init />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased h-full bg-background text-foreground">
        <div class="min-h-full flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="fixed top-4 right-4">
                <x-theme-selector icon-only />
            </div>

            <div class="mb-8">
                <x-ui.logo href="/" size="lg" text-class="text-xl font-semibold tracking-tight" />
            </div>

            <div class="w-full sm:max-w-md px-4">
                <div class="rounded-lg border border-border bg-card text-card-foreground shadow-sm px-6 py-8">
                    {{ $slot }}
                </div>

                <div class="mt-6 text-center text-sm text-muted-foreground">
                    <p>&copy; {{ date('Y') }} GestorPro. Todos os direitos reservados.</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div data-flash-payload='@json(['message' => session('success'), 'type' => 'success'])' class="hidden"></div>
        @endif

        @if(session('error'))
            <div data-flash-payload='@json(['message' => session('error'), 'type' => 'error'])' class="hidden"></div>
        @endif

        @if(session('warning'))
            <div data-flash-payload='@json(['message' => session('warning'), 'type' => 'warning'])' class="hidden"></div>
        @endif

        @if(session('info'))
            <div data-flash-payload='@json(['message' => session('info'), 'type' => 'info'])' class="hidden"></div>
        @endif

        @if($errors->any())
            <div id="flash-errors" data-errors='@json($errors->all())' class="hidden"></div>
        @endif
    </body>
</html>
