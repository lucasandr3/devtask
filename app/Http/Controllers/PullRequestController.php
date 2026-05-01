<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePullRequestRequest;
use App\Http\Requests\UpdatePullRequestRequest;
use App\Models\PullRequest;
use Carbon\Carbon;

class PullRequestController extends Controller
{
    public function index()
    {
        $pullRequests = PullRequest::where('user_id', auth()->id())
            ->with('task')
            ->orderBy('work_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('pull-requests.index', compact('pullRequests'));
    }

    public function create()
    {
        $tasks = \App\Models\Task::where('user_id', auth()->id())
            ->orderBy('work_date', 'desc')
            ->orderBy('title')
            ->get();
        
        return view('pull-requests.create', compact('tasks'));
    }

    public function store(StorePullRequestRequest $request)
    {
        // Valida se a tarefa pertence ao usuário
        if ($request->task_id) {
            $task = \App\Models\Task::where('id', $request->task_id)
                ->where('user_id', auth()->id())
                ->first();
            
            if (!$task) {
                return back()->withErrors(['task_id' => 'Tarefa inválida.'])->withInput();
            }
        }

        PullRequest::create([
            'user_id' => auth()->id(),
            'repo' => $request->repo,
            'pr_number' => $request->pr_number,
            'title' => $request->title,
            'url' => $request->url,
            'status' => $request->status,
            'work_date' => Carbon::parse($request->work_date),
            'task_id' => $request->task_id,
        ]);

        return redirect()->route('pull-requests.index')
            ->with('success', 'Pull Request registrado com sucesso!');
    }

    public function edit(PullRequest $pullRequest)
    {
        if ($pullRequest->user_id !== auth()->id()) {
            abort(403);
        }

        $tasks = \App\Models\Task::where('user_id', auth()->id())
            ->orderBy('work_date', 'desc')
            ->orderBy('title')
            ->get();

        return view('pull-requests.edit', compact('pullRequest', 'tasks'));
    }

    public function update(UpdatePullRequestRequest $request, PullRequest $pullRequest)
    {
        if ($pullRequest->user_id !== auth()->id()) {
            abort(403);
        }

        // Valida se a tarefa pertence ao usuário
        if ($request->task_id) {
            $task = \App\Models\Task::where('id', $request->task_id)
                ->where('user_id', auth()->id())
                ->first();
            
            if (!$task) {
                return back()->withErrors(['task_id' => 'Tarefa inválida.'])->withInput();
            }
        }

        $pullRequest->update([
            'repo' => $request->repo,
            'pr_number' => $request->pr_number,
            'title' => $request->title,
            'url' => $request->url,
            'status' => $request->status,
            'work_date' => Carbon::parse($request->work_date),
            'task_id' => $request->task_id,
        ]);

        return redirect()->route('pull-requests.index')
            ->with('success', 'Pull Request atualizado com sucesso!');
    }

    public function destroy(PullRequest $pullRequest)
    {
        if ($pullRequest->user_id !== auth()->id()) {
            abort(403);
        }

        $pullRequest->delete();

        return redirect()->route('pull-requests.index')
            ->with('success', 'Pull Request excluído com sucesso!');
    }
}
