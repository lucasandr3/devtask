@props(['type', 'label', 'enabled' => true, 'currentTime' => null])

<form method="POST" action="{{ route('horas.registrar.salvar') }}" class="w-full">
    @csrf
    <input type="hidden" name="type" value="{{ $type }}">
    
    <div class="flex flex-col items-center gap-5 w-full">
        <div class="w-full">
            <label for="time-{{ $type }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 text-center">
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
                    class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-slate-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-center text-xl font-mono font-bold tracking-widest"
                    oninput="formatTimeInput(this)"
                    onkeypress="return validateTimeKey(event)"
                />
                <button
                    type="button"
                    onclick="setCurrentTime('time-{{ $type }}')"
                    class="px-4 py-3 text-sm font-medium bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-slate-600 transition-colors whitespace-nowrap"
                    title="Usar hora atual"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </button>
            </div>
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 text-center">
                Deixe em branco para usar a hora atual
            </p>
        </div>
        
        <button
            type="submit"
            @if(!$enabled) disabled @endif
            class="w-full px-8 py-4 text-lg font-semibold rounded-xl shadow-lg transition-all duration-200
                {{ $enabled 
                    ? 'bg-primary-600 dark:bg-primary-500 hover:bg-primary-700 dark:hover:bg-primary-600 text-white hover:shadow-xl transform hover:-translate-y-0.5' 
                    : 'bg-gray-200 dark:bg-slate-700 text-gray-400 dark:text-gray-500 cursor-not-allowed' 
                }}"
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
