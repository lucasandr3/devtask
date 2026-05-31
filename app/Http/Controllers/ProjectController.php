<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Support\CurrentCompany;
use Carbon\Carbon;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = CurrentCompany::projectsQuery()
            ->withCount('tasks')
            ->with('creator')
            ->orderByDesc('created_at')
            ->get();

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        $tasksQuery = fn ($q) => CurrentCompany::isMember()
            ? $q->where('assigned_to', auth()->id())
            : $q;

        $project->load(['creator', 'tasks' => $tasksQuery, 'tasks.assignee', 'tasks.timeEntries']);

        $tasksByStatus = [
            'todo' => $project->tasks->where('status', TaskStatus::TODO)->values(),
            'doing' => $project->tasks->where('status', TaskStatus::DOING)->values(),
            'done' => $project->tasks->where('status', TaskStatus::DONE)->values(),
            'cancelled' => $project->tasks->where('status', TaskStatus::CANCELLED)->values(),
        ];

        $members = auth()->user()->currentCompany?->users ?? collect();
        $activeTimerTaskId = TimeEntry::where('user_id', auth()->id())
            ->whereNull('ended_at')
            ->value('task_id');

        return view('projects.show', compact('project', 'tasksByStatus', 'members', 'activeTimerTaskId'));
    }

    public function create()
    {
        abort_unless(CurrentCompany::canManageProjects(), 403);

        $clients = \App\Models\Client::where('company_id', CurrentCompany::id())->orderBy('name')->get();

        return view('projects.create', compact('clients'));
    }

    public function store(StoreProjectRequest $request)
    {
        abort_unless(CurrentCompany::canManageProjects(), 403);

        Project::create([
            'company_id' => CurrentCompany::id(),
            'created_by' => auth()->id(),
            'client_id' => $request->client_id,
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'starts_at' => $request->starts_at ? Carbon::parse($request->starts_at) : null,
            'ends_at' => $request->ends_at ? Carbon::parse($request->ends_at) : null,
            'budget' => $request->budget,
            'hourly_rate' => $request->hourly_rate,
        ]);

        return redirect()->route('projetos.index')
            ->with('success', 'Projeto criado com sucesso!');
    }

    public function edit(Project $project)
    {
        abort_unless(CurrentCompany::canManageProjects(), 403);

        $clients = \App\Models\Client::where('company_id', CurrentCompany::id())->orderBy('name')->get();

        return view('projects.edit', compact('project', 'clients'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        abort_unless(CurrentCompany::canManageProjects(), 403);

        $project->update([
            'client_id' => $request->client_id,
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'starts_at' => $request->starts_at ? Carbon::parse($request->starts_at) : null,
            'ends_at' => $request->ends_at ? Carbon::parse($request->ends_at) : null,
            'budget' => $request->budget,
            'hourly_rate' => $request->hourly_rate,
        ]);

        return redirect()->route('projetos.show', $project)
            ->with('success', 'Projeto atualizado com sucesso!');
    }

    public function destroy(Project $project)
    {
        abort_unless(CurrentCompany::canManageProjects(), 403);

        $project->delete();

        return redirect()->route('projetos.index')
            ->with('success', 'Projeto excluído com sucesso!');
    }
}
