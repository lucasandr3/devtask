<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Support\CurrentCompany;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->get('view', 'table');

        $query = CurrentCompany::tasksQuery()
            ->with(['project', 'assignee'])
            ->withCount('pullRequests');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->has('status') && $view === 'table') {
            $query->where('status', $request->status);
        }

        if ($request->has('work_date')) {
            $query->where('work_date', $request->work_date);
        }

        $projects = CurrentCompany::projectsQuery()->orderBy('name')->get();

        $activeTimerTaskId = TimeEntry::where('user_id', auth()->id())
            ->whereNull('ended_at')
            ->value('task_id');

        if ($view === 'kanban') {
            $allTasks = $query->orderBy('work_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            $tasksByStatus = [
                'todo' => $allTasks->where('status', TaskStatus::TODO)->values(),
                'doing' => $allTasks->where('status', TaskStatus::DOING)->values(),
                'done' => $allTasks->where('status', TaskStatus::DONE)->values(),
                'cancelled' => $allTasks->where('status', TaskStatus::CANCELLED)->values(),
            ];

            return view('tasks.index', compact('tasksByStatus', 'view', 'projects', 'activeTimerTaskId'));
        }

        $allTasks = $query->orderBy('work_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $tasksByProject = collect();

        if ($request->filled('project_id')) {
            $tasksByProject->push([
                'project' => $projects->firstWhere('id', (int) $request->project_id),
                'tasks' => $allTasks,
            ]);
        } else {
            foreach ($projects as $project) {
                $projectTasks = $allTasks->where('project_id', $project->id)->values();

                if ($projectTasks->isNotEmpty()) {
                    $tasksByProject->push([
                        'project' => $project,
                        'tasks' => $projectTasks,
                    ]);
                }
            }

            $orphanTasks = $allTasks->whereNull('project_id')->values();

            if ($orphanTasks->isNotEmpty()) {
                $tasksByProject->push([
                    'project' => null,
                    'tasks' => $orphanTasks,
                ]);
            }
        }

        return view('tasks.index', compact('tasksByProject', 'view', 'projects', 'activeTimerTaskId'));
    }

    public function show(Task $task)
    {
        $task->load([
            'project',
            'assignee',
            'creator',
            'timeEntries' => fn ($query) => $query->with('user')->orderByDesc('started_at'),
            'pullRequests',
        ]);

        $canManage = CurrentCompany::canManageProjects();
        $isAssignee = $task->isAssignedToCurrentUser();
        $runningEntry = $task->runningTimeEntryFor();
        $activeTimerTaskId = TimeEntry::where('user_id', auth()->id())
            ->whereNull('ended_at')
            ->value('task_id');

        return view('tasks.show', compact(
            'task',
            'canManage',
            'isAssignee',
            'runningEntry',
            'activeTimerTaskId',
        ));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:todo,doing,done,cancelled',
        ]);

        $task->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status atualizado com sucesso!',
            'task' => $task,
        ]);
    }

    public function create(Request $request)
    {
        abort_unless(CurrentCompany::canManageProjects(), 403);

        $projects = Project::where('company_id', CurrentCompany::id())
            ->orderBy('name')
            ->get();

        $members = auth()->user()->currentCompany?->users ?? collect();

        return view('tasks.create', compact('projects', 'members'));
    }

    public function store(StoreTaskRequest $request)
    {
        abort_unless(CurrentCompany::canManageProjects(), 403);

        $assignedTo = $request->assigned_to ?: auth()->id();

        Task::create([
            'user_id' => $assignedTo,
            'project_id' => $request->project_id,
            'assigned_to' => $assignedTo,
            'created_by' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'work_date' => Carbon::parse($request->work_date),
        ]);

        $redirect = $request->project_id
            ? route('projetos.show', $request->project_id)
            : route('tarefas.index');

        return redirect($redirect)->with('success', 'Tarefa criada com sucesso!');
    }

    public function edit(Task $task)
    {
        $task->load(['project', 'timeEntries']);

        $projects = CurrentCompany::projectsQuery()->orderBy('name')->get();
        $members = auth()->user()->currentCompany?->users ?? collect();
        $canManage = CurrentCompany::canManageProjects();
        $activeTimerTaskId = TimeEntry::where('user_id', auth()->id())
            ->whereNull('ended_at')
            ->value('task_id');

        return view('tasks.edit', compact('task', 'projects', 'members', 'canManage', 'activeTimerTaskId'));
    }

    public function update(Request $request, Task $task)
    {
        if (! CurrentCompany::canManageProjects() && ! $task->isAssignedToCurrentUser()) {
            abort(403);
        }

        $redirectToShow = $request->boolean('redirect_to_show');

        if (CurrentCompany::canManageProjects()) {
            if ($redirectToShow) {
                $validated = $request->validate([
                    'status' => 'required|in:todo,doing,done,cancelled',
                    'description' => 'nullable|string',
                    'executor_notes' => 'nullable|string',
                    'internal_notes' => 'nullable|string',
                ]);

                $task->update($validated);
            } else {
                $validated = $request->validate((new UpdateTaskRequest())->rules());
                $assignedTo = $validated['assigned_to'] ?? $task->assigned_to;

                $task->update([
                    'project_id' => $validated['project_id'],
                    'user_id' => $assignedTo,
                    'assigned_to' => $assignedTo,
                    'title' => $validated['title'],
                    'description' => $validated['description'] ?? null,
                    'executor_notes' => $validated['executor_notes'] ?? $task->executor_notes,
                    'internal_notes' => $validated['internal_notes'] ?? $task->internal_notes,
                    'status' => $validated['status'],
                    'work_date' => Carbon::parse($validated['work_date']),
                ]);
            }
        } else {
            $validated = $request->validate([
                'status' => 'required|in:todo,doing,done,cancelled',
                'description' => 'nullable|string',
                'executor_notes' => 'nullable|string',
            ]);

            $task->update($validated);
        }

        $redirectRoute = $redirectToShow
            ? route('tarefas.show', $task)
            : route('tarefas.index');

        return redirect($redirectRoute)
            ->with('success', 'Tarefa atualizada com sucesso!');
    }

    public function destroy(Task $task)
    {
        abort_unless(CurrentCompany::canManageProjects(), 403);

        $task->delete();

        return redirect()->route('tarefas.index')
            ->with('success', 'Tarefa excluída com sucesso!');
    }
}
