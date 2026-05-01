<?php

namespace App\Http\Controllers;

use App\Models\DailyPoint;
use App\Models\Task;
use App\Models\PullRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now()->locale('pt_BR');
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // Horas trabalhadas no mês
        $points = DailyPoint::where('user_id', auth()->id())
            ->whereBetween('work_date', [$startOfMonth, $endOfMonth])
            ->get();

        $totalMinutes = $points->sum('total_minutes');
        $normalMinutes = $points->sum('normal_minutes');
        $extraMinutes = $points->sum('extra_minutes');

        // Tarefas concluídas no mês
        $tasksDone = Task::where('user_id', auth()->id())
            ->where('status', 'done')
            ->whereBetween('work_date', [$startOfMonth, $endOfMonth])
            ->count();

        // PRs entregues no mês
        $prsDelivered = PullRequest::where('user_id', auth()->id())
            ->whereBetween('work_date', [$startOfMonth, $endOfMonth])
            ->count();

        // Últimos pontos registrados
        $recentPoints = DailyPoint::where('user_id', auth()->id())
            ->orderBy('work_date', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', [
            'totalHours' => minutesToHours($totalMinutes),
            'normalHours' => minutesToHours($normalMinutes),
            'extraHours' => minutesToHours($extraMinutes),
            'tasksDone' => $tasksDone,
            'prsDelivered' => $prsDelivered,
            'recentPoints' => $recentPoints,
            'currentMonth' => $now->translatedFormat('F Y'),
        ]);
    }
}
