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
        <link href="https://fonts.bunny.net/css?family=roboto:400,500,600,700|material-symbols-outlined:400&display=swap" rel="stylesheet" />

        <x-theme-init :sidebar="true" />
        
        <style>
            @media (min-width: 1024px) {
                .sidebar-brand { width: 16rem; transition: width 0.3s ease-in-out; }
                .main-content { transition: margin-left 0.3s ease-in-out; margin-left: 16rem; }
                [data-sidebar-collapsed="true"] .sidebar-brand { width: 4rem; }
                [data-sidebar-collapsed="true"] .sidebar-brand-text { display: none; }
                [data-sidebar-collapsed="true"] .main-content { margin-left: 4rem; }
            }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body
        class="font-sans antialiased h-full bg-background text-foreground"
        x-data="{
            mobileSidebarOpen: false,
            toggleMobileSidebar() { this.mobileSidebarOpen = !this.mobileSidebarOpen },
            closeMobileSidebar() { this.mobileSidebarOpen = false }
        }"
        @toggle-mobile-sidebar.window="toggleMobileSidebar()"
        @keydown.escape.window="closeMobileSidebar()"
        x-effect="document.body.style.overflow = mobileSidebarOpen ? 'hidden' : ''"
    >
        @include('layouts.top-header')
        @include('layouts.mobile-header')

        <div class="min-h-full flex pt-14 lg:pt-14">
            @include('layouts.sidebar')

            <div class="main-content flex-1 min-h-[calc(100vh-3.5rem)] min-w-0 bg-background">
                <main class="py-6">
                    <div class="w-full px-4 sm:px-6 lg:px-8">
                        @isset($slot)
                            {{ $slot }}
                        @else
                            @yield('content')
                        @endisset
                    </div>
                </main>
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

        @stack('scripts')
    </body>
</html>
