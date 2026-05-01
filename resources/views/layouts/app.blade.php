<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DevTask') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Prevent FOUC for dark mode and sidebar -->
        <script>
            (function() {
                const darkMode = localStorage.getItem('devtask-dark-mode');
                const theme = localStorage.getItem('devtask-theme');
                const sidebarCollapsed = localStorage.getItem('sidebar-collapsed');
                
                if (darkMode === 'true' || (darkMode === null && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
                
                if (theme && theme !== 'blue') {
                    document.documentElement.setAttribute('data-theme', theme);
                }
                
                // Set sidebar state for initial render
                document.documentElement.setAttribute('data-sidebar-collapsed', sidebarCollapsed === 'true' ? 'true' : 'false');
            })();
        </script>
        
        <!-- Sidebar initial state styles -->
        <style>
            @media (min-width: 1024px) {
                .main-content {
                    transition: margin-left 0.3s ease-in-out;
                }
                [data-sidebar-collapsed="true"] .main-content { margin-left: 5rem; }
                [data-sidebar-collapsed="false"] .main-content { margin-left: 16rem; }
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased h-full">
        <div class="min-h-full bg-gradient-to-br from-gray-50 via-gray-100 to-gray-50 dark-bg-main">
            {{-- Sidebar --}}
            @include('layouts.sidebar')

            {{-- Mobile Header --}}
            @include('layouts.mobile-header')

            {{-- Main Content Area --}}
            <div class="main-content min-h-screen">
                <!-- Top Header Bar -->
                <header class="sticky top-0 z-30 h-16 bg-white/80 backdrop-blur-xl border-b border-gray-200/50 hidden lg:flex items-center justify-between px-6 dark-header">
                    {{-- Page Title --}}
                    <div class="flex items-center">
                        @isset($header)
                            <div class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ $header }}
                            </div>
                        @endisset
                    </div>

                    {{-- Right Side: Theme Controls --}}
                    <div class="flex items-center gap-3">
                        <x-theme-selector />
                        <x-dark-mode-toggle />
                    </div>
                </header>

                <!-- Page Content -->
                <main class="py-6 pt-20 lg:pt-8">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        @if($errors->any())
                            <div class="mb-6 alert-error" role="alert">
                                <div class="flex items-center gap-3 mb-2">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-medium">Ocorreram erros:</span>
                                </div>
                                <ul class="list-disc list-inside ml-8 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @isset($slot)
                            {{ $slot }}
                        @else
                            @yield('content')
                        @endisset
                    </div>
                </main>
            </div>
        </div>
        
        <!-- Toast Container -->
        <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-3"></div>
        
        @if(session('success'))
            <div id="flash-success" data-message="{{ session('success') }}" class="hidden"></div>
        @endif
        
        @stack('scripts')
        
        <!-- Toast System -->
        <script>
            window.Toast = {
                show: function(message, type = 'success', duration = 4000) {
                    const container = document.getElementById('toast-container');
                    const toast = document.createElement('div');
                    
                    const colors = {
                        success: 'bg-green-600',
                        error: 'bg-red-600',
                        warning: 'bg-amber-600',
                        info: 'bg-blue-600'
                    };
                    
                    const icons = {
                        success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>',
                        error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>',
                        warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>',
                        info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                    };
                    
                    toast.className = `flex items-center gap-3 px-4 py-3 rounded-xl shadow-2xl text-white ${colors[type]} transform translate-x-full opacity-0 transition-all duration-300 min-w-[300px] max-w-md`;
                    toast.innerHTML = `
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${icons[type]}
                        </svg>
                        <span class="flex-1 font-medium">${message}</span>
                        <button onclick="this.parentElement.remove()" class="p-1 hover:bg-white/20 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    `;
                    
                    container.appendChild(toast);
                    
                    // Animate in
                    requestAnimationFrame(() => {
                        toast.classList.remove('translate-x-full', 'opacity-0');
                    });
                    
                    // Auto remove
                    setTimeout(() => {
                        toast.classList.add('translate-x-full', 'opacity-0');
                        setTimeout(() => toast.remove(), 300);
                    }, duration);
                }
            };
            
            // Check for flash messages
            document.addEventListener('DOMContentLoaded', function() {
                var flashSuccess = document.getElementById('flash-success');
                if (flashSuccess) {
                    Toast.show(flashSuccess.dataset.message, 'success');
                    flashSuccess.remove();
                }
            });
        </script>
    </body>
</html>
