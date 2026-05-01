<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveMonthlyReportRequest;
use App\Http\Requests\RejectMonthlyReportRequest;
use App\Models\MonthlyReport;
use App\Services\MonthlyReportService;
use App\Services\PdfService;
use App\Services\FinancialReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MonthlyReportController extends Controller
{
    public function __construct(
        private MonthlyReportService $monthlyReportService,
        private PdfService $pdfService,
        private FinancialReportService $financialReportService
    ) {}

    public function index(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $date = Carbon::createFromFormat('Y-m', $month)->startOfMonth();

        // Buscar relatório existente para o mês
        $report = MonthlyReport::where('user_id', auth()->id())
            ->whereYear('reference_month', $date->year)
            ->whereMonth('reference_month', $date->month)
            ->first();

        // Dados financeiros do mês
        $financialData = $this->financialReportService->getMonthlyFinancial(
            auth()->id(),
            $month
        );

        // Se existe relatório, pegar os daily points dele
        if ($report) {
            $dailyPoints = $report->dailyPoints;
            $tasks = $report->user->tasks()
                ->with('pullRequests')
                ->whereYear('work_date', $date->year)
                ->whereMonth('work_date', $date->month)
                ->orderBy('work_date')
                ->get();
        } else {
            // Se não existe, buscar dados brutos para preview
            $dailyPoints = auth()->user()->dailyPoints()
                ->whereYear('work_date', $date->year)
                ->whereMonth('work_date', $date->month)
                ->orderBy('work_date')
                ->get();
            $tasks = auth()->user()->tasks()
                ->with('pullRequests')
                ->whereYear('work_date', $date->year)
                ->whereMonth('work_date', $date->month)
                ->orderBy('work_date')
                ->get();
        }

        // Calcular totais
        $totalMinutes = $dailyPoints->sum('total_minutes');
        $normalMinutes = $dailyPoints->sum('normal_minutes');
        $extraMinutes = $dailyPoints->sum('extra_minutes');
        $workedDays = $dailyPoints->count();

        // Contrato de horas (buscar do work contract ativo ou usar padrão)
        $contract = auth()->user()->workContracts()
            ->active($date)
            ->first();
        $contractMinutes = $contract ? $contract->monthly_minutes : 220 * 60;

        $balanceMinutes = $totalMinutes - $contractMinutes;

        return view('monthly-reports.index', compact(
            'month',
            'date',
            'report',
            'dailyPoints',
            'tasks',
            'financialData',
            'totalMinutes',
            'normalMinutes',
            'extraMinutes',
            'workedDays',
            'contractMinutes',
            'balanceMinutes'
        ));
    }

    public function generate(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));

        try {
            $this->monthlyReportService->generate(auth()->id(), $month);
            return redirect()->route('relatorios-mensais.index', ['month' => $month])
                ->with('success', 'Relatório gerado com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(MonthlyReport $monthlyReport)
    {
        if ($monthlyReport->user_id !== auth()->id()) {
            abort(403);
        }

        // Redirecionar para o index com o mês correto
        return redirect()->route('relatorios-mensais.index', [
            'month' => $monthlyReport->reference_month->format('Y-m')
        ]);
    }

    public function pdf(MonthlyReport $monthlyReport)
    {
        if ($monthlyReport->user_id !== auth()->id()) {
            abort(403);
        }

        return $this->pdfService->generateMonthlyReport($monthlyReport);
    }

    public function hoursMirror(MonthlyReport $monthlyReport)
    {
        if ($monthlyReport->user_id !== auth()->id()) {
            abort(403);
        }

        return $this->pdfService->generateHoursMirror($monthlyReport);
    }

    public function send(int $id)
    {
        $report = MonthlyReport::where('user_id', auth()->id())->findOrFail($id);

        try {
            $this->monthlyReportService->sendForApproval($report);
            return back()->with('success', 'Relatório enviado para aprovação!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function approve(ApproveMonthlyReportRequest $request, int $id)
    {
        $report = MonthlyReport::findOrFail($id);

        $this->authorize('approve', $report);

        try {
            $this->monthlyReportService->approve($report, $request->approver_name);
            return back()->with('success', 'Relatório aprovado com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function reject(RejectMonthlyReportRequest $request, int $id)
    {
        $report = MonthlyReport::findOrFail($id);

        $this->authorize('reject', $report);

        try {
            $this->monthlyReportService->reject($report, $request->notes);
            return back()->with('success', 'Relatório rejeitado.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
