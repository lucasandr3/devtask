<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col justify-between">
            <h2 class="page-title">Registrar Hora</h2>
            <p class="text-sm text-muted-foreground">{{ $today->format('d/m/Y') }}</p>
        </div>
    </x-slot>

    <div class="w-full">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <!-- Today's Points Summary - Ocupa 3 colunas -->
            <div class="lg:col-span-3">
                <div class="card p-6 h-full">
                    <h3 class="text-lg font-semibold text-foreground mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Horas Registradas Hoje
                    </h3>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="p-4 bg-muted/50 bg-secondary/50 rounded-xl">
                            <span class="text-xs text-muted-foreground uppercase tracking-wide">Entrada</span>
                            <p class="text-2xl font-bold text-foreground mt-1">
                                {{ $point && $point->entry_time ? $point->entry_time->format('H:i') : '--:--' }}
                            </p>
                        </div>
                        <div class="p-4 bg-muted/50 bg-secondary/50 rounded-xl">
                            <span class="text-xs text-muted-foreground uppercase tracking-wide">Saída Almoço</span>
                            <p class="text-2xl font-bold text-foreground mt-1">
                                {{ $point && $point->lunch_out_time ? $point->lunch_out_time->format('H:i') : '--:--' }}
                            </p>
                        </div>
                        <div class="p-4 bg-muted/50 bg-secondary/50 rounded-xl">
                            <span class="text-xs text-muted-foreground uppercase tracking-wide">Volta Almoço</span>
                            <p class="text-2xl font-bold text-foreground mt-1">
                                {{ $point && $point->lunch_return_time ? $point->lunch_return_time->format('H:i') : '--:--' }}
                            </p>
                        </div>
                        <div class="p-4 bg-muted/50 bg-secondary/50 rounded-xl">
                            <span class="text-xs text-muted-foreground uppercase tracking-wide">Saída</span>
                            <p class="text-2xl font-bold text-foreground mt-1">
                                {{ $point && $point->exit_time ? $point->exit_time->format('H:i') : '--:--' }}
                            </p>
                        </div>
                    </div>

                    @if($point && ($point->extra_start_time || $point->extra_end_time))
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            @if($point->extra_start_time)
                                <div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800">
                                    <span class="text-xs text-amber-600 dark:text-amber-400 uppercase tracking-wide">Início Extra</span>
                                    <p class="text-2xl font-bold text-amber-700 dark:text-amber-300 mt-1">
                                        {{ $point->extra_start_time->format('H:i') }}
                                    </p>
                                </div>
                            @endif
                            @if($point->extra_end_time)
                                <div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800">
                                    <span class="text-xs text-amber-600 dark:text-amber-400 uppercase tracking-wide">Fim Extra</span>
                                    <p class="text-2xl font-bold text-amber-700 dark:text-amber-300 mt-1">
                                        {{ $point->extra_end_time->format('H:i') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Total Hours -->
                    <div class="pt-6 border-t border-border">
                        <div class="flex items-center justify-between">
                            <span class="text-muted-foreground">Total trabalhado:</span>
                            <span class="text-4xl font-bold text-primary">
                                {{ $point ? $point->total_hours_formatted : '00:00' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Punch Action - Ocupa 2 colunas -->
            <div class="lg:col-span-2">
                <div class="card p-6 h-full flex flex-col">
                    <h3 class="text-lg font-semibold text-foreground mb-6 text-center">
                        @if($nextPunchType)
                            Próximo Registro
                        @else
                            Status do Dia
                        @endif
                    </h3>
                    
                    <div class="flex-1 flex flex-col justify-center">
                        @if($nextPunchType)
                            <x-punch-button 
                                :type="$nextPunchType->value" 
                                :label="$nextPunchType->label()"
                                :enabled="true"
                            />
                        @elseif(!$point)
                            <x-punch-button 
                                type="entry" 
                                label="Entrada"
                                :enabled="true"
                            />
                        @else
                            <div class="flex flex-col items-center text-center">
                                <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <p class="text-muted-foreground mb-6">Todas as horas foram registradas.</p>
                                
                                <a href="{{ route('horas.index') }}" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 bg-secondary text-foreground font-semibold rounded-xl hover:bg-gray-300 hover:bg-accent transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Ver Histórico
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
