<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="GestorPro - Sistema de gestão de projetos, equipes, horas e relatórios para sua empresa">

        <title>{{ config('app.name', 'GestorPro') }} - Gestão de Projetos</title>

        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        <link rel="apple-touch-icon" href="{{ asset('web-app-manifest-192x192.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Prevent FOUC for dark mode -->
        <script>
            (function() {
                const darkMode = localStorage.getItem('gestorpro-dark-mode');
                const theme = localStorage.getItem('gestorpro-theme');
                
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

        <style>
            /* Animações customizadas */
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            @keyframes float-delayed {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-15px); }
            }
            @keyframes pulse-slow {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.5; }
            }
            @keyframes gradient-shift {
                0%, 100% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
            }
            .animate-float { animation: float 6s ease-in-out infinite; }
            .animate-float-delayed { animation: float-delayed 5s ease-in-out infinite; animation-delay: 1s; }
            .animate-pulse-slow { animation: pulse-slow 4s ease-in-out infinite; }
            .gradient-animate {
                background-size: 200% 200%;
                animation: gradient-shift 15s ease infinite;
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
            }
            .dark .glass-card {
                background: rgba(30, 41, 59, 0.7);
            }
            /* Scroll reveal */
            .reveal {
                opacity: 0;
                transform: translateY(30px);
                transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .reveal.active {
                opacity: 1;
                transform: translateY(0);
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-muted/50 dark:bg-slate-950 text-foreground overflow-x-hidden">
        
        <!-- Navbar Fixa -->
        <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" id="navbar">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <x-ui.logo href="/" size="lg" text-class="text-xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent" />

                    <!-- Nav Links (Desktop) -->
                    <div class="hidden md:flex items-center gap-8">
                        <a href="#features" class="text-sm font-medium text-muted-foreground hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            Recursos
                        </a>
                        <a href="#how-it-works" class="text-sm font-medium text-muted-foreground hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            Como Funciona
                        </a>
                        <a href="#testimonials" class="text-sm font-medium text-muted-foreground hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            Depoimentos
                        </a>
                    </div>

                    <!-- Auth Buttons -->
                    <div class="flex items-center gap-3">
                        <!-- Dark Mode Toggle -->
                        <button onclick="toggleDarkMode()" class="p-2 rounded-lg text-muted-foreground hover:bg-accent transition-colors">
                            <svg class="w-5 h-5 hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"/>
                            </svg>
                            <svg class="w-5 h-5 block dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                            </svg>
                        </button>

                        @auth
                            <a href="{{ route('painel') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300 hover:-translate-y-0.5">
                                Dashboard
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('entrar') }}" class="hidden sm:inline-flex text-sm font-medium text-muted-foreground hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                Entrar
                            </a>
                            @if (Route::has('registrar'))
                                <a href="{{ route('registrar') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300 hover:-translate-y-0.5">
                                    Começar Grátis
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative min-h-screen flex items-center justify-center pt-20 overflow-hidden">
            <!-- Background Decorations -->
            <div class="absolute inset-0 overflow-hidden">
                <!-- Gradient Orbs -->
                <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500/20 dark:bg-blue-500/10 rounded-full blur-3xl animate-float"></div>
                <div class="absolute top-1/3 -left-40 w-80 h-80 bg-purple-500/20 dark:bg-purple-500/10 rounded-full blur-3xl animate-float-delayed"></div>
                <div class="absolute -bottom-40 right-1/4 w-72 h-72 bg-cyan-500/20 dark:bg-cyan-500/10 rounded-full blur-3xl animate-float"></div>
                
                <!-- Grid Pattern -->
                <div class="absolute inset-0 bg-[linear-gradient(rgba(59,130,246,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(59,130,246,0.03)_1px,transparent_1px)] bg-[size:60px_60px] dark:bg-[linear-gradient(rgba(59,130,246,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(59,130,246,0.05)_1px,transparent_1px)]"></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-32">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                    <!-- Left Content -->
                    <div class="text-center lg:text-left">
                        <!-- Badge -->
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 dark:bg-blue-950/50 border border-blue-200 dark:border-blue-800 rounded-full mb-8">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                            </span>
                            <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Gestão de projetos para sua empresa</span>
                        </div>

                        <!-- Headline -->
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                            <span class="text-foreground">Gerencie seu trabalho</span>
                            <br>
                            <span class="bg-gradient-to-r from-blue-600 via-blue-500 to-cyan-500 bg-clip-text text-transparent gradient-animate">
                                como um profissional
                            </span>
                        </h1>

                        <!-- Subheadline -->
                        <p class="text-lg sm:text-xl text-muted-foreground mb-10 max-w-xl mx-auto lg:mx-0">
                            Controle tarefas, registre horas, gerencie pull requests e gere relatórios mensais automatizados. Tudo em um só lugar.
                        </p>

                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-12">
                            @auth
                                <a href="{{ route('painel') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-lg font-semibold rounded-2xl shadow-xl shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300 hover:-translate-y-1">
                                    Acessar Dashboard
                                    <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </a>
                            @else
                                <a href="{{ route('registrar') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-lg font-semibold rounded-2xl shadow-xl shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300 hover:-translate-y-1">
                                    Começar Gratuitamente
                                    <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </a>
                                <a href="{{ route('entrar') }}" class="inline-flex items-center justify-center px-8 py-4 bg-card text-foreground text-lg font-semibold rounded-2xl border-2 border-border hover:border-blue-500 dark:hover:border-blue-500 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                    </svg>
                                    Já tenho conta
                                </a>
                            @endauth
                        </div>

                        <!-- Social Proof -->
                        <div class="flex items-center justify-center lg:justify-start gap-6">
                            <div class="flex -space-x-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-sm font-bold border-2 border-white dark:border-slate-900">JD</div>
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white text-sm font-bold border-2 border-white dark:border-slate-900">AS</div>
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white text-sm font-bold border-2 border-white dark:border-slate-900">MR</div>
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white text-sm font-bold border-2 border-white dark:border-slate-900">+5</div>
                            </div>
                            <div class="text-left">
                                <div class="flex items-center gap-1">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-sm text-muted-foreground">Usado por <span class="font-semibold text-foreground">50+</span> desenvolvedores</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Content - Dashboard Preview -->
                    <div class="relative">
                        <!-- Floating Cards -->
                        <div class="relative">
                            <!-- Main Dashboard Card -->
                            <div class="glass-card rounded-3xl border border-border/50 border-border/50 shadow-2xl p-6 transform hover:scale-[1.02] transition-transform duration-500">
                                <!-- Header -->
                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        <h3 class="text-lg font-bold text-foreground">Dashboard</h3>
                                        <p class="text-sm text-muted-foreground">Visão geral do seu dia</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                        <div class="w-3 h-3 rounded-full bg-green-400"></div>
                                    </div>
                                </div>

                                <!-- Stats Grid -->
                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-950/50 dark:to-blue-900/30 rounded-2xl p-4">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="p-2 bg-blue-500 rounded-lg">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                            </div>
                                            <span class="text-2xl font-bold text-foreground">12</span>
                                        </div>
                                        <p class="text-xs text-muted-foreground">Tarefas ativas</p>
                                    </div>
                                    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-950/50 dark:to-green-900/30 rounded-2xl p-4">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="p-2 bg-green-500 rounded-lg">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <span class="text-2xl font-bold text-foreground">8h</span>
                                        </div>
                                        <p class="text-xs text-muted-foreground">Horas trabalhadas</p>
                                    </div>
                                </div>

                                <!-- Task List Preview -->
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3 p-3 bg-muted/50/50 rounded-xl">
                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-foreground">Implementar API REST</p>
                                            <p class="text-xs text-muted-foreground">Em progresso</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300 rounded-full">75%</span>
                                    </div>
                                    <div class="flex items-center gap-3 p-3 bg-muted/50/50 rounded-xl">
                                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-foreground">Code Review PR #42</p>
                                            <p class="text-xs text-muted-foreground">Concluído</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300 rounded-full">100%</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Floating Notification Card -->
                            <div class="absolute -top-4 -right-4 glass-card rounded-2xl border border-border/50 border-border/50 shadow-xl p-4 animate-float-delayed">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/50 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-foreground">Hora registrada!</p>
                                        <p class="text-xs text-muted-foreground">Entrada às 09:00</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Floating Stats Card -->
                            <div class="absolute -bottom-4 -left-4 glass-card rounded-2xl border border-border/50 border-border/50 shadow-xl p-4 animate-float">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold text-foreground">+28%</p>
                                        <p class="text-xs text-muted-foreground">Produtividade este mês</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
                <a href="#features" class="flex flex-col items-center gap-2 text-gray-400 hover:text-blue-500 transition-colors">
                    <span class="text-xs font-medium">Saiba mais</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </a>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="relative py-20 bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 dark:from-blue-900 dark:via-blue-950 dark:to-indigo-950 overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0 bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.1)_50%,transparent_75%,transparent_100%)] bg-[length:20px_20px]"></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="text-center reveal">
                        <div class="text-4xl sm:text-5xl font-extrabold text-white mb-2">50+</div>
                        <p class="text-blue-200 font-medium">Desenvolvedores Ativos</p>
                    </div>
                    <div class="text-center reveal" style="transition-delay: 100ms;">
                        <div class="text-4xl sm:text-5xl font-extrabold text-white mb-2">1.2k+</div>
                        <p class="text-blue-200 font-medium">Tarefas Gerenciadas</p>
                    </div>
                    <div class="text-center reveal" style="transition-delay: 200ms;">
                        <div class="text-4xl sm:text-5xl font-extrabold text-white mb-2">500+</div>
                        <p class="text-blue-200 font-medium">PRs Registrados</p>
                    </div>
                    <div class="text-center reveal" style="transition-delay: 300ms;">
                        <div class="text-4xl sm:text-5xl font-extrabold text-white mb-2">99.9%</div>
                        <p class="text-blue-200 font-medium">Uptime Garantido</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-24 sm:py-32 bg-white bg-background">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Header -->
                <div class="text-center mb-20 reveal">
                    <span class="inline-block px-4 py-2 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 text-sm font-semibold rounded-full mb-4">
                        Recursos
                    </span>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-foreground mb-6">
                        Tudo que você precisa em
                        <span class="text-blue-600 dark:text-blue-400">um só lugar</span>
                    </h2>
                    <p class="text-lg text-muted-foreground max-w-3xl mx-auto">
                        Ferramentas poderosas para gerenciar seu trabalho diário como desenvolvedor, desde tarefas até relatórios mensais.
                    </p>
                </div>

                <!-- Features Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Feature 1 -->
                    <div class="group relative reveal">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-blue-600 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 blur-xl"></div>
                        <div class="relative h-full bg-muted/50 rounded-3xl p-8 border border-gray-100 border-border hover:border-blue-500/50 dark:hover:border-blue-500/50 transition-all duration-500 hover:-translate-y-2">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-500">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-foreground mb-3">Gestão de Tarefas</h3>
                            <p class="text-muted-foreground leading-relaxed">
                                Crie, organize e acompanhe suas tarefas com status, prioridades e datas de entrega.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="group relative reveal" style="transition-delay: 100ms;">
                        <div class="absolute inset-0 bg-gradient-to-r from-green-500 to-emerald-600 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 blur-xl"></div>
                        <div class="relative h-full bg-muted/50 rounded-3xl p-8 border border-gray-100 border-border hover:border-green-500/50 dark:hover:border-green-500/50 transition-all duration-500 hover:-translate-y-2">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-green-500/30 group-hover:scale-110 transition-transform duration-500">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-foreground mb-3">Controle de Horas</h3>
                            <p class="text-muted-foreground leading-relaxed">
                                Registre entradas e saídas com um clique. Histórico completo e relatórios automáticos.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="group relative reveal" style="transition-delay: 200ms;">
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-violet-600 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 blur-xl"></div>
                        <div class="relative h-full bg-muted/50 rounded-3xl p-8 border border-gray-100 border-border hover:border-purple-500/50 dark:hover:border-purple-500/50 transition-all duration-500 hover:-translate-y-2">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-violet-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform duration-500">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-foreground mb-3">Relatórios Mensais</h3>
                            <p class="text-muted-foreground leading-relaxed">
                                Gere relatórios mensais completos em PDF com todas as suas atividades consolidadas.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 4 -->
                    <div class="group relative reveal" style="transition-delay: 300ms;">
                        <div class="absolute inset-0 bg-gradient-to-r from-orange-500 to-amber-600 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 blur-xl"></div>
                        <div class="relative h-full bg-muted/50 rounded-3xl p-8 border border-gray-100 border-border hover:border-orange-500/50 dark:hover:border-orange-500/50 transition-all duration-500 hover:-translate-y-2">
                            <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-orange-500/30 group-hover:scale-110 transition-transform duration-500">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-foreground mb-3">Pull Requests</h3>
                            <p class="text-muted-foreground leading-relaxed">
                                Registre e acompanhe seus PRs com links, descrições e status de revisão.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="py-24 sm:py-32 bg-muted/50 dark:bg-slate-950">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Header -->
                <div class="text-center mb-20 reveal">
                    <span class="inline-block px-4 py-2 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 text-sm font-semibold rounded-full mb-4">
                        Como Funciona
                    </span>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-foreground mb-6">
                        Simples de usar,
                        <span class="text-blue-600 dark:text-blue-400">poderoso de verdade</span>
                    </h2>
                    <p class="text-lg text-muted-foreground max-w-3xl mx-auto">
                        Em apenas três passos você estará gerenciando seu trabalho como nunca antes.
                    </p>
                </div>

                <!-- Steps -->
                <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
                    <!-- Step 1 -->
                    <div class="relative reveal">
                        <div class="text-center">
                            <div class="relative inline-flex mb-8">
                                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-xl shadow-blue-500/30">
                                    <span class="text-3xl font-extrabold text-white">1</span>
                                </div>
                                <!-- Connector Line -->
                                <div class="hidden md:block absolute top-1/2 left-full w-full h-0.5 bg-gradient-to-r from-blue-500 to-transparent transform -translate-y-1/2"></div>
                            </div>
                            <h3 class="text-xl font-bold text-foreground mb-4">Crie sua conta</h3>
                            <p class="text-muted-foreground">
                                Registre-se gratuitamente em segundos. Sem cartão de crédito, sem complicações.
                            </p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative reveal" style="transition-delay: 150ms;">
                        <div class="text-center">
                            <div class="relative inline-flex mb-8">
                                <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-xl shadow-green-500/30">
                                    <span class="text-3xl font-extrabold text-white">2</span>
                                </div>
                                <!-- Connector Line -->
                                <div class="hidden md:block absolute top-1/2 left-full w-full h-0.5 bg-gradient-to-r from-green-500 to-transparent transform -translate-y-1/2"></div>
                            </div>
                            <h3 class="text-xl font-bold text-foreground mb-4">Configure seu contrato</h3>
                            <p class="text-muted-foreground">
                                Defina seu contrato de trabalho com carga horária e período de vigência.
                            </p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative reveal" style="transition-delay: 300ms;">
                        <div class="text-center">
                            <div class="relative inline-flex mb-8">
                                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-violet-600 rounded-2xl flex items-center justify-center shadow-xl shadow-purple-500/30">
                                    <span class="text-3xl font-extrabold text-white">3</span>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-foreground mb-4">Comece a gerenciar</h3>
                            <p class="text-muted-foreground">
                                Registre horas, crie tarefas e gere relatórios automaticamente. Simples assim!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section id="testimonials" class="py-24 sm:py-32 bg-white bg-background">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Header -->
                <div class="text-center mb-20 reveal">
                    <span class="inline-block px-4 py-2 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 text-sm font-semibold rounded-full mb-4">
                        Depoimentos
                    </span>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-foreground mb-6">
                        O que nossos usuários
                        <span class="text-blue-600 dark:text-blue-400">dizem</span>
                    </h2>
                </div>

                <!-- Testimonials Grid -->
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Testimonial 1 -->
                    <div class="reveal">
                        <div class="h-full glass-card rounded-3xl p-8 border border-border/50 border-border/50 hover:shadow-xl transition-shadow duration-500">
                            <div class="flex items-center gap-1 mb-6">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-muted-foreground mb-6 leading-relaxed">
                                "O GestorPro revolucionou minha forma de trabalhar. Agora tenho controle total sobre minhas horas e tarefas. Os relatórios mensais são perfeitos!"
                            </p>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                    JD
                                </div>
                                <div>
                                    <p class="font-semibold text-foreground">João Dev</p>
                                    <p class="text-sm text-muted-foreground">Full Stack Developer</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="reveal" style="transition-delay: 100ms;">
                        <div class="h-full glass-card rounded-3xl p-8 border border-border/50 border-border/50 hover:shadow-xl transition-shadow duration-500">
                            <div class="flex items-center gap-1 mb-6">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-muted-foreground mb-6 leading-relaxed">
                                "Finalmente um sistema que entende as necessidades de um desenvolvedor freelancer. O controle de horas é preciso e os relatórios são profissionais."
                            </p>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold">
                                    AS
                                </div>
                                <div>
                                    <p class="font-semibold text-foreground">Ana Silva</p>
                                    <p class="text-sm text-muted-foreground">Frontend Developer</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="reveal" style="transition-delay: 200ms;">
                        <div class="h-full glass-card rounded-3xl p-8 border border-border/50 border-border/50 hover:shadow-xl transition-shadow duration-500">
                            <div class="flex items-center gap-1 mb-6">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-muted-foreground mb-6 leading-relaxed">
                                "Interface limpa, funcionalidades completas e fácil de usar. O GestorPro se tornou essencial no meu dia a dia como desenvolvedor."
                            </p>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                    MR
                                </div>
                                <div>
                                    <p class="font-semibold text-foreground">Marcos Reis</p>
                                    <p class="text-sm text-muted-foreground">Backend Developer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA Section -->
        <section class="relative py-24 sm:py-32 overflow-hidden">
            <!-- Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 dark:from-blue-900 dark:via-indigo-900 dark:to-purple-950"></div>
            
            <!-- Decorative Elements -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-20 -right-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            </div>

            <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white mb-6">
                    Pronto para transformar sua
                    <span class="text-blue-200">produtividade?</span>
                </h2>
                <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto">
                    Junte-se a empresas que já gerenciam projetos, equipes e horas de forma mais inteligente com o GestorPro.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @auth
                        <a href="{{ route('painel') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-white text-blue-600 text-lg font-semibold rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                            Acessar Dashboard
                            <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('registrar') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-white text-blue-600 text-lg font-semibold rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                            Começar Gratuitamente
                            <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="{{ route('entrar') }}" class="inline-flex items-center justify-center px-8 py-4 bg-transparent text-white text-lg font-semibold rounded-2xl border-2 border-white/30 hover:border-white/60 hover:bg-white/10 transition-all duration-300">
                            Já tenho conta
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 dark:bg-black py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-4 gap-12 mb-12">
                    <!-- Brand -->
                    <div class="md:col-span-2">
                        <x-ui.logo href="/" size="md" text-class="text-xl font-bold text-white" class="mb-6" />
                        <p class="text-gray-400 max-w-md">
                            Sistema completo de gestão para desenvolvedores. Controle suas tarefas, horas e relatórios em um só lugar.
                        </p>
                    </div>

                    <!-- Links -->
                    <div>
                        <h4 class="text-white font-semibold mb-4">Produto</h4>
                        <ul class="space-y-3">
                            <li><a href="#features" class="text-gray-400 hover:text-white transition-colors">Recursos</a></li>
                            <li><a href="#how-it-works" class="text-gray-400 hover:text-white transition-colors">Como Funciona</a></li>
                            <li><a href="#testimonials" class="text-gray-400 hover:text-white transition-colors">Depoimentos</a></li>
                        </ul>
                    </div>

                    <!-- Legal -->
                    <div>
                        <h4 class="text-white font-semibold mb-4">Legal</h4>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Termos de Uso</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Privacidade</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Bottom -->
                <div class="pt-8 border-t border-gray-800">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <p class="text-muted-foreground text-sm">
                            &copy; {{ date('Y') }} {{ config('app.name', 'GestorPro') }}. Todos os direitos reservados.
                        </p>
                        <div class="flex items-center gap-4">
                            <a href="#" class="text-muted-foreground hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Scripts -->
        <script>
            // Dark Mode Toggle
            function toggleDarkMode() {
                const isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('gestorpro-dark-mode', isDark);
            }

            // Navbar Background on Scroll
            const navbar = document.getElementById('navbar');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbar.classList.add('bg-white/90', 'bg-background/90', 'backdrop-blur-lg', 'shadow-lg');
                } else {
                    navbar.classList.remove('bg-white/90', 'bg-background/90', 'backdrop-blur-lg', 'shadow-lg');
                }
            });

            // Scroll Reveal Animation
            const revealElements = document.querySelectorAll('.reveal');
            
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            revealElements.forEach(el => revealObserver.observe(el));

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        </script>
    </body>
</html>
