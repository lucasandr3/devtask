@props(['type', 'label', 'enabled' => true, 'currentTime' => null])

<form method="POST" action="{{ route('horas.registrar.salvar') }}" class="w-full">
    @csrf
    <input type="hidden" name="type" value="{{ $type }}">
    
    <div class="flex flex-col items-center gap-5 w-full">
        <div class="w-full">
            <label for="time-{{ $type }}" class="block text-sm font-medium text-foreground mb-2 text-center">
                Horário (opcional)
            </label>
            <div class="flex items-center gap-2">
                <input 
                    type="text" 
                    id="time-{{ $type }}" 
                    name="time" 
                    value="{{ $currentTime ? \Carbon\Carbon::parse($currentTime)->format('H:i') : '' }}"
                    placeholder="00:00"
                    maxlength="5"
                    class="input flex-1 text-center text-xl font-mono font-bold tracking-widest"
                    oninput="formatTimeInput(this)"
                    onkeypress="return validateTimeKey(event)"
                />
                <button
                    type="button"
                    onclick="setCurrentTime('time-{{ $type }}')"
                    class="inline-flex items-center justify-center h-10 px-4 text-sm font-medium rounded-md bg-secondary text-secondary-foreground hover:bg-secondary/80 transition-colors whitespace-nowrap ui-tooltip ui-tooltip-top"
                    data-tooltip="Usar hora atual"
                    aria-label="Usar hora atual"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </button>
            </div>
            <p class="mt-2 text-xs text-muted-foreground text-center">
                Deixe em branco para usar a hora atual
            </p>
        </div>
        
        <button
            type="submit"
            @if(!$enabled) disabled @endif
            @class([
                'w-full px-8 py-4 text-lg font-semibold rounded-md shadow transition-all duration-200',
                'bg-primary text-primary-foreground hover:bg-primary/90 hover:shadow-md' => $enabled,
                'bg-muted text-muted-foreground cursor-not-allowed opacity-50' => !$enabled,
            ])
        >
            {{ $label }}
        </button>
    </div>
</form>

@push('scripts')
<script>
function formatTimeInput(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value.length > 4) {
        value = value.substring(0, 4);
    }
    
    if (value.length >= 3) {
        const hours = value.substring(0, 2);
        const minutes = value.substring(2, 4);
        input.value = hours + ':' + minutes;
    } else if (value.length >= 1) {
        input.value = value;
    }
}

function validateTimeKey(event) {
    const char = String.fromCharCode(event.which);
    if (!/[0-9]/.test(char)) {
        event.preventDefault();
        return false;
    }
    return true;
}

function setCurrentTime(inputId) {
    const input = document.getElementById(inputId);
    if (input) {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        input.value = hours + ':' + minutes;
    }
}
</script>
@endpush
