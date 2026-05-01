<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('pull-requests.index') }}" class="p-2 bg-gray-100 dark:bg-slate-700 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="page-title">Novo Pull Request</h2>
        </div>
    </x-slot>

    <div>
        <div class="card p-6">
            <form method="POST" action="{{ route('pull-requests.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="repo" value="Repositorio" />
                        <x-text-input type="text" name="repo" id="repo" value="{{ old('repo') }}" required class="mt-1" placeholder="org/repo" />
                        <x-input-error :messages="$errors->get('repo')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="pr_number" value="Numero do PR" />
                        <x-text-input type="number" name="pr_number" id="pr_number" value="{{ old('pr_number') }}" required class="mt-1" placeholder="123" />
                        <x-input-error :messages="$errors->get('pr_number')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="title" value="Titulo" />
                    <x-text-input type="text" name="title" id="title" value="{{ old('title') }}" required class="mt-1" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="url" value="URL" />
                    <x-text-input type="url" name="url" id="url" value="{{ old('url') }}" required class="mt-1" placeholder="https://github.com/..." />
                    <x-input-error :messages="$errors->get('url')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="task_id" value="Tarefa (Opcional)" />
                    <div class="relative mt-1">
                        <div class="relative">
                            <input 
                                type="text" 
                                id="task_search" 
                                class="input w-full pr-10" 
                                placeholder="Pesquisar tarefa..." 
                                autocomplete="off"
                            />
                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <div id="task_dropdown" class="hidden absolute z-50 w-full mt-1 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-lg shadow-lg max-h-60 overflow-auto">
                            <div class="p-2">
                                <div class="task-option cursor-pointer px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-slate-700" data-value="">
                                    <span class="text-gray-500 dark:text-gray-400">Nenhuma tarefa</span>
                                </div>
                                @foreach($tasks as $task)
                                    <div class="task-option cursor-pointer px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-slate-700" data-value="{{ $task->id }}" data-title="{{ strtolower($task->title) }}" data-date="{{ $task->work_date->format('d/m/Y') }}">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $task->title }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $task->work_date->format('d/m/Y') }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="task_id" id="task_id_hidden" value="{{ old('task_id') }}">
                    <select id="task_id_select" class="hidden">
                        @foreach($tasks as $task)
                            <option value="{{ $task->id }}" {{ old('task_id') == $task->id ? 'selected' : '' }}>
                                {{ $task->title }} - {{ $task->work_date->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Vincule este PR a uma tarefa específica</p>
                    <x-input-error :messages="$errors->get('task_id')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="status" value="Status" />
                        <select name="status" id="status" required class="input mt-1 w-full">
                            <option value="open" {{ old('status', 'open') == 'open' ? 'selected' : '' }}>Aberto</option>
                            <option value="merged" {{ old('status') == 'merged' ? 'selected' : '' }}>Mergeado</option>
                            <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Fechado</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="work_date" value="Data de Trabalho" />
                        <x-text-input type="text" name="work_date" id="work_date" value="{{ old('work_date', date('Y-m-d')) }}" required class="mt-1" data-datepicker placeholder="Selecione a data" />
                        <x-input-error :messages="$errors->get('work_date')" class="mt-2" />
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-slate-700">
                    <a href="{{ route('pull-requests.index') }}" class="btn-secondary">Cancelar</a>
                    <x-primary-button>Salvar</x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('task_search');
            const dropdown = document.getElementById('task_dropdown');
            const hiddenInput = document.getElementById('task_id_hidden');
            const select = document.getElementById('task_id_select');
            const options = document.querySelectorAll('.task-option');
            
            let selectedTask = null;
            
            // Inicializa com o valor selecionado
            const selectedOption = select ? select.querySelector('option[selected]') : null;
            if (selectedOption && selectedOption.value) {
                const taskText = selectedOption.textContent;
                searchInput.value = taskText;
                selectedTask = {
                    id: selectedOption.value,
                    text: taskText
                };
                hiddenInput.value = selectedOption.value;
            }
            
            // Mostra/esconde dropdown
            function toggleDropdown(show) {
                if (show) {
                    dropdown.classList.remove('hidden');
                } else {
                    setTimeout(() => dropdown.classList.add('hidden'), 200);
                }
            }
            
            // Filtra opções
            function filterOptions(searchTerm) {
                const term = searchTerm.toLowerCase();
                options.forEach(option => {
                    const title = option.getAttribute('data-title') || '';
                    const date = option.getAttribute('data-date') || '';
                    const text = (title + ' ' + date).toLowerCase();
                    
                    if (text.includes(term) || term === '') {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                });
            }
            
            // Seleciona uma opção
            function selectOption(option) {
                const value = option.getAttribute('data-value');
                const title = option.querySelector('.font-medium')?.textContent || option.textContent.trim();
                const date = option.querySelector('.text-sm')?.textContent || '';
                
                if (value === '') {
                    searchInput.value = '';
                    hiddenInput.value = '';
                    selectedTask = null;
                } else {
                    searchInput.value = title + (date ? ' - ' + date : '');
                    hiddenInput.value = value;
                    selectedTask = { id: value, text: searchInput.value };
                }
                
                toggleDropdown(false);
            }
            
            // Event listeners
            searchInput.addEventListener('focus', () => toggleDropdown(true));
            searchInput.addEventListener('input', (e) => {
                filterOptions(e.target.value);
                toggleDropdown(true);
            });
            
            options.forEach(option => {
                option.addEventListener('click', () => selectOption(option));
            });
            
            // Fecha dropdown ao clicar fora
            document.addEventListener('click', (e) => {
                if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                    toggleDropdown(false);
                    // Restaura o valor selecionado se houver
                    if (selectedTask) {
                        searchInput.value = selectedTask.text;
                    }
                }
            });
        });
    </script>
</x-app-layout>

