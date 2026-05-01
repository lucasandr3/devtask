<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->get('view', 'table');
        
        $query = Task::where('user_id', auth()->id())
            ->withCount('pullRequests');

        if ($request->has('status') && $view === 'table') {
            $query->where('status', $request->status);
        }

        if ($request->has('work_date')) {
            $query->where('work_date', $request->work_date);
        }

        // Para view Kanban, agrupar por status
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
            
            return view('tasks.index', compact('tasksByStatus', 'view'));
        }

        $tasks = $query->orderBy('work_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('tasks.index', compact('tasks', 'view'));
    }

    /**
     * Atualiza apenas o status de uma tarefa (para Kanban drag & drop)
     */
    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:todo,doing,done,cancelled',
        ]);

        $task->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status atualizado com sucesso!',
            'task' => $task,
        ]);
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(StoreTaskRequest $request)
    {
        Task::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'work_date' => Carbon::parse($request->work_date),
        ]);

        return redirect()->route('tarefas.index')
            ->with('success', 'Tarefa criada com sucesso!');
    }

    public function edit(Task $task)
    {
        $task->load('pullRequests');
        return view('tasks.edit', compact('task'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'work_date' => Carbon::parse($request->work_date),
        ]);

        return redirect()->route('tarefas.index')
            ->with('success', 'Tarefa atualizada com sucesso!');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tarefas.index')
            ->with('success', 'Tarefa excluída com sucesso!');
    }
}
