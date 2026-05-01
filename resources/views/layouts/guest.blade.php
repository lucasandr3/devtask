<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DevTask') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Prevent FOUC for dark mode -->
        <script>
            (function() {
                const darkMode = localStorage.getItem('devtask-dark-mode');
                const theme = localStorage.getItem('devtask-theme');
                
                if (darkMode === 'true' || (darkMode === null && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
                
                if (theme && theme !== 'blue') {
                    document.documentElement.setAttribute('data-theme', theme);
                }
            })();
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased h-full">
        <div class="min-h-full flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-primary-50 via-gray-50 to-primary-100 dark:from-slate-900 dark:via-slate-900 dark:to-slate-800">
            <!-- Dark Mode Toggle -->
            <div class="fixed top-4 right-4">
                <x-dark-mode-toggle />
            </div>

            <!-- Logo -->
            <div class="mb-6">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="p-3 bg-primary-600 rounded-xl shadow-lg group-hover:bg-primary-700 transition-colors duration-200">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white">DevTask</span>
                </a>
            </div>

            <!-- Card -->
            <div class="w-full sm:max-w-md">
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl shadow-xl rounded-2xl border border-gray-200/50 dark:border-slate-700/50 px-8 py-8">
                    {{ $slot }}
                </div>

                <!-- Footer Links -->
                <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
                    <p>&copy; {{ date('Y') }} DevTask. Todos os direitos reservados.</p>
                </div>
            </div>
        </div>
    </body>
</html>
