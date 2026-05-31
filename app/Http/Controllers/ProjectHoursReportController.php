<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\TimeEntry;
use App\Support\CurrentCompany;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectHoursReportController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(CurrentCompany::canViewCompanyReports(), 403);

        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $companyId = CurrentCompany::id();

        $start = Carbon::parse($month.'-01')->startOfMonth();
        $end = Carbon::parse($month.'-01')->endOfMonth();

        $projectHours = TimeEntry::query()
            ->select('projects.id', 'projects.name', DB::raw('SUM(time_entries.duration_minutes) as total_minutes'))
            ->join('tasks', 'tasks.id', '=', 'time_entries.task_id')
            ->join('projects', 'projects.id', '=', 'tasks.project_id')
            ->where('projects.company_id', $companyId)
            ->whereNotNull('time_entries.ended_at')
            ->whereBetween('time_entries.started_at', [$start, $end])
            ->groupBy('projects.id', 'projects.name')
            ->orderByDesc('total_minutes')
            ->get();

        $memberHours = TimeEntry::query()
            ->select('users.id', 'users.name', DB::raw('SUM(time_entries.duration_minutes) as total_minutes'))
            ->join('users', 'users.id', '=', 'time_entries.user_id')
            ->join('tasks', 'tasks.id', '=', 'time_entries.task_id')
            ->join('projects', 'projects.id', '=', 'tasks.project_id')
            ->where('projects.company_id', $companyId)
            ->whereNotNull('time_entries.ended_at')
            ->whereBetween('time_entries.started_at', [$start, $end])
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_minutes')
            ->get();

        $recentEntries = TimeEntry::with(['user', 'task.project'])
            ->whereHas('task.project', fn ($q) => $q->where('company_id', $companyId))
            ->whereNotNull('ended_at')
            ->whereBetween('started_at', [$start, $end])
            ->orderByDesc('started_at')
            ->limit(50)
            ->get();

        $projects = Project::where('company_id', $companyId)->orderBy('name')->get();

        return view('reports.project-hours', [
            'month' => $month,
            'projectHours' => $projectHours,
            'memberHours' => $memberHours,
            'recentEntries' => $recentEntries,
            'projects' => $projects,
            'monthLabel' => $start->locale('pt_BR')->translatedFormat('F Y'),
        ]);
    }
}
