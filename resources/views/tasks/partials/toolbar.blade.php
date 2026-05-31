@if(isset($projects) && $projects->isNotEmpty())
    <form method="GET" action="{{ route('tarefas.index') }}" class="flex items-center">
        @if($view !== 'table')
            <input type="hidden" name="view" value="{{ $view }}">
        @endif
        <x-ui.select-input name="project_id" class="w-full sm:w-52" onchange="this.form.submit()">
            <option value="">Todos os projetos</option>
            @foreach($projects as $project)
                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                    {{ $project->name }}
                </option>
            @endforeach
        </x-ui.select-input>
    </form>
@endif
