<?php

namespace App\Services;

use App\Models\MonthlyReport;
use App\Models\AnnualDeclaration;
use App\Services\WorkContractService;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;

class PdfService
{
    public function generateMonthlyReport(MonthlyReport $report): Response
    {
        $previousLocale = Carbon::getLocale();
        Carbon::setLocale('pt_BR');

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);

        $html = View::make('pdf.monthly-report', [
            'report' => $report,
            'dailyPoints' => $report->dailyPoints,
            'tasks' => $report->user->tasks()
                ->with('pullRequests')
                ->whereYear('work_date', $report->reference_month->year)
                ->whereMonth('work_date', $report->reference_month->month)
                ->orderBy('work_date')
                ->get(),
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        Carbon::setLocale($previousLocale);

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="relatorio-mensal-' . $report->reference_month->format('Y-m') . '.pdf"',
        ]);
    }

    public function generateHoursMirror(MonthlyReport $report): Response
    {
        $previousLocale = Carbon::getLocale();
        Carbon::setLocale('pt_BR');

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);

        $html = View::make('pdf.hours-mirror', [
            'report' => $report,
            'dailyPoints' => $report->dailyPoints,
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        Carbon::setLocale($previousLocale);

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="espelho-horas-' . $report->reference_month->format('Y-m') . '.pdf"',
        ]);
    }

    /**
     * Gera o PDF do espelho de horas para um período específico (sem MonthlyReport)
     * Retorna o conteúdo do PDF como string para uso em anexos de email
     */
    public function generateHoursMirrorPdf(int $userId, string $month): string
    {
        $date = Carbon::createFromFormat('Y-m', $month);
        $user = \App\Models\User::findOrFail($userId);

        // Garantir mês e datas em português no PDF
        $previousLocale = Carbon::getLocale();
        Carbon::setLocale('pt_BR');

        $dailyPoints = \App\Models\DailyPoint::where('user_id', $userId)
            ->whereYear('work_date', $date->year)
            ->whereMonth('work_date', $date->month)
            ->orderBy('work_date')
            ->get();

        // Calcular totais
        $totalMinutes = $dailyPoints->sum('total_minutes');
        $totalNormalMinutes = $dailyPoints->sum('normal_minutes');
        $totalExtraMinutes = $dailyPoints->sum('extra_minutes');

        // Carga horária contratual do contrato ativo no mês (ex.: 168h)
        $workContractService = app(WorkContractService::class);
        $contractMinutes = $workContractService->getMonthlyMinutesForDate(
            $userId,
            $date->copy()->day(15)
        );
        $balanceMinutes = $totalMinutes - $contractMinutes;

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);

        // Criar objeto fake para compatibilidade com a view
        $report = new \stdClass();
        $report->reference_month = $date;
        $report->user = $user;
        $report->contract_hours_formatted = minutesToHours($contractMinutes);
        $report->balance_minutes = $balanceMinutes;
        $report->balance_hours_formatted = minutesToHours($balanceMinutes);

        $html = View::make('pdf.hours-mirror', [
            'report' => $report,
            'dailyPoints' => $dailyPoints,
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        Carbon::setLocale($previousLocale);

        return $dompdf->output();
    }

    /**
     * Gera o PDF do relatório financeiro para um período específico
     * Retorna o conteúdo do PDF como string
     */
    public function generateFinancialReportPdf(int $userId, string $month, array $financialData): string
    {
        $date = Carbon::createFromFormat('Y-m', $month);
        $user = \App\Models\User::findOrFail($userId);

        $previousLocale = Carbon::getLocale();
        Carbon::setLocale('pt_BR');

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);

        $html = View::make('pdf.financial-report', [
            'user' => $user,
            'date' => $date,
            'financialData' => $financialData,
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        Carbon::setLocale($previousLocale);

        return $dompdf->output();
    }

    public function generateAnnualDeclaration(AnnualDeclaration $declaration): Response
    {
        $previousLocale = Carbon::getLocale();
        Carbon::setLocale('pt_BR');

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);

        $html = View::make('pdf.annual-declaration', [
            'declaration' => $declaration,
            'invoices' => $declaration->invoices,
            'dasPayments' => $declaration->dasPayments,
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        Carbon::setLocale($previousLocale);

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="declaracao-anual-' . $declaration->reference_year . '.pdf"',
        ]);
    }

    /**
     * Gera o PDF do relatório de tarefas e pull requests
     */
    public function generateTasksReportPdf(int $userId, string $month): string
    {
        $date = Carbon::createFromFormat('Y-m', $month);
        $user = \App\Models\User::findOrFail($userId);

        $previousLocale = Carbon::getLocale();
        Carbon::setLocale('pt_BR');

        $tasks = \App\Models\Task::where('user_id', $userId)
            ->with('pullRequests')
            ->whereYear('work_date', $date->year)
            ->whereMonth('work_date', $date->month)
            ->orderBy('work_date')
            ->get();

        // Estatísticas
        $totalTasks = $tasks->count();
        $doneTasks = $tasks->where('status', \App\Enums\TaskStatus::DONE)->count();
        $doingTasks = $tasks->where('status', \App\Enums\TaskStatus::DOING)->count();
        $todoTasks = $tasks->where('status', \App\Enums\TaskStatus::TODO)->count();
        $totalPRs = $tasks->sum(fn($task) => $task->pullRequests->count());

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);

        $html = View::make('pdf.tasks-report', [
            'user' => $user,
            'date' => $date,
            'tasks' => $tasks,
            'totalTasks' => $totalTasks,
            'doneTasks' => $doneTasks,
            'doingTasks' => $doingTasks,
            'todoTasks' => $todoTasks,
            'totalPRs' => $totalPRs,
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        Carbon::setLocale($previousLocale);

        return $dompdf->output();
    }
}
