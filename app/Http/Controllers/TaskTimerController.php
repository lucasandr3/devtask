<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TimeEntry;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class TaskTimerController extends Controller
{
    public function start(Task $task): JsonResponse
    {
        $running = TimeEntry::where('user_id', auth()->id())
            ->whereNull('ended_at')
            ->first();

        if ($running) {
            if ($running->task_id === $task->id) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cronômetro já está ativo nesta tarefa.',
                    'entry' => $running,
                ]);
            }

            $running->stop();
        }

        $entry = TimeEntry::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'started_at' => Carbon::now(),
        ]);

        if ($task->status->value === 'todo') {
            $task->update(['status' => 'doing']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cronômetro iniciado!',
            'entry' => $entry,
            'task' => [
                'id' => $task->id,
                'title' => $task->title,
            ],
        ]);
    }

    public function stop(Task $task): JsonResponse
    {
        $entry = TimeEntry::where('user_id', auth()->id())
            ->where('task_id', $task->id)
            ->whereNull('ended_at')
            ->first();

        if (!$entry) {
            return response()->json([
                'success' => false,
                'message' => 'Não há cronômetro ativo nesta tarefa.',
            ], 422);
        }

        $entry->stop();

        return response()->json([
            'success' => true,
            'message' => 'Cronômetro parado!',
            'entry' => $entry->fresh(),
            'total_minutes' => $task->fresh()->totalTrackedMinutes(),
            'total_minutes_label' => minutesToHours($task->fresh()->totalTrackedMinutes()),
        ]);
    }

    public function active(): JsonResponse
    {
        $entry = TimeEntry::with('task.project')
            ->where('user_id', auth()->id())
            ->whereNull('ended_at')
            ->first();

        if (!$entry) {
            return response()->json(['active' => false]);
        }

        return response()->json([
            'active' => true,
            'entry' => $entry,
            'elapsed_seconds' => (int) $entry->started_at->diffInSeconds(Carbon::now()),
            'task' => [
                'id' => $entry->task->id,
                'title' => $entry->task->title,
                'project' => $entry->task->project?->name,
            ],
            'total_minutes' => $entry->task->totalTrackedMinutes(),
            'total_minutes_label' => minutesToHours($entry->task->totalTrackedMinutes()),
        ]);
    }
}
