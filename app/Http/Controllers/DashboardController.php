<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Enums\TaskStatus;
use App\Models\DailyPoint;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Support\CurrentCompany;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now()->locale('pt_BR');
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        $userId = auth()->id();
        $isMember = CurrentCompany::isMember();

        $taskQuery = CurrentCompany::tasksQuery();

        $tasksDone = (clone $taskQuery)
            ->where('status', 'done')
            ->whereBetween('work_date', [$startOfMonth, $endOfMonth])
            ->count();

        $tasksInProgress = (clone $taskQuery)
            ->where('status', 'doing')
            ->count();

        $trackedMinutes = TimeEntry::where('user_id', $userId)
            ->whereHas('task.project', fn ($q) => $q->where('company_id', CurrentCompany::id()))
            ->whereBetween('started_at', [$startOfMonth, $endOfMonth])
            ->whereNotNull('ended_at')
            ->sum('duration_minutes');

        $punchMinutes = DailyPoint::where('user_id', $userId)
            ->whereBetween('work_date', [$startOfMonth, $endOfMonth])
            ->sum('total_minutes');

        $recentProjects = CurrentCompany::projectsQuery()
            ->withCount(['tasks' => fn ($q) => $isMember ? $q->where('assigned_to', $userId) : $q])
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get();

        $activeProjects = $isMember
            ? $recentProjects->where('status', ProjectStatus::ACTIVE)->count()
            : Project::where('company_id', CurrentCompany::id())
                ->where('status', ProjectStatus::ACTIVE)
                ->count();

        return view('dashboard.index', [
            'activeProjects' => $activeProjects,
            'tasksDone' => $tasksDone,
            'tasksInProgress' => $tasksInProgress,
            'trackedHours' => minutesToHours($trackedMinutes),
            'punchHours' => minutesToHours($punchMinutes),
            'recentProjects' => $recentProjects,
            'currentMonth' => $now->translatedFormat('F Y'),
            'company' => CurrentCompany::get(),
            'isMember' => $isMember,
        ]);
    }
}
