<?php

namespace App\Services;

use App\Enums\DasPaymentStatus;
use App\Enums\FinancialTransactionStatus;
use App\Enums\FinancialTransactionType;
use App\Enums\InvoicePaymentStatus;
use App\Models\FinancialTransaction;
use App\Models\Invoice;
use App\Models\DasPayment;
use App\Models\TimeEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CompanyFinancialService
{
    public function __construct(
        private FinancialReportService $financialReportService
    ) {}

    public function getMonthlyFinancial(int $companyId, string $month): array
    {
        $base = $this->financialReportService->getMonthlyFinancialForCompany($companyId, $month);

        $date = Carbon::createFromFormat('Y-m', $month);
        $start = $date->copy()->startOfMonth();
        $end = $date->copy()->endOfMonth();

        $receivables = FinancialTransaction::where('company_id', $companyId)
            ->where('type', FinancialTransactionType::RECEIVABLE)
            ->whereBetween('due_date', [$start, $end])
            ->get();

        $payables = FinancialTransaction::where('company_id', $companyId)
            ->where('type', FinancialTransactionType::PAYABLE)
            ->whereBetween('due_date', [$start, $end])
            ->get();

        $totalReceivablePaid = $receivables->where('status', FinancialTransactionStatus::PAID)->sum('amount');
        $totalReceivablePending = $receivables->where('status', FinancialTransactionStatus::PENDING)->sum('amount');
        $totalPayablePaid = $payables->where('status', FinancialTransactionStatus::PAID)->sum('amount');
        $totalPayablePending = $payables->where('status', FinancialTransactionStatus::PENDING)->sum('amount');

        $pendingInvoices = $base['invoices']->where('payment_status', InvoicePaymentStatus::PENDING)->sum('valor');
        $overdueInvoices = $base['invoices']->where('payment_status', InvoicePaymentStatus::OVERDUE)->sum('valor');

        $totalInflows = $base['total_revenue'] + $totalReceivablePaid;
        $totalReceivableOpen = $totalReceivablePending + $pendingInvoices + $overdueInvoices;
        $totalExpenses = $base['total_das_paid'] + $totalPayablePaid;
        $totalPendingOut = $base['total_das_pending'] + $base['total_das_overdue'] + $totalPayablePending;
        $balance = $totalInflows - $totalExpenses;

        return array_merge($base, [
            'receivables' => $receivables,
            'payables' => $payables,
            'total_inflows' => $totalInflows,
            'total_receivable_paid' => $totalReceivablePaid,
            'total_receivable_pending' => $totalReceivablePending,
            'total_receivable_open' => $totalReceivableOpen,
            'total_payable_paid' => $totalPayablePaid,
            'total_payable_pending' => $totalPayablePending,
            'pending_invoices_amount' => $pendingInvoices,
            'overdue_invoices_amount' => $overdueInvoices,
            'total_expenses' => $totalExpenses,
            'total_pending_out' => $totalPendingOut,
            'balance' => $balance,
            'formatted_total_inflows' => $this->formatMoney($totalInflows),
            'formatted_total_receivable_paid' => $this->formatMoney($totalReceivablePaid),
            'formatted_total_receivable_pending' => $this->formatMoney($totalReceivablePending),
            'formatted_total_receivable_open' => $this->formatMoney($totalReceivableOpen),
            'formatted_total_payable_paid' => $this->formatMoney($totalPayablePaid),
            'formatted_total_payable_pending' => $this->formatMoney($totalPayablePending),
            'formatted_total_expenses' => $this->formatMoney($totalExpenses),
            'formatted_total_pending_out' => $this->formatMoney($totalPendingOut),
            'formatted_balance' => $this->formatMoney($balance),
        ]);
    }

    public function getProjectMargins(int $companyId, string $month): array
    {
        $date = Carbon::createFromFormat('Y-m', $month);
        $start = $date->copy()->startOfMonth();
        $end = $date->copy()->endOfMonth();

        $hoursByProject = TimeEntry::query()
            ->select('projects.id', 'projects.name', 'projects.budget', 'projects.hourly_rate', DB::raw('SUM(time_entries.duration_minutes) as total_minutes'))
            ->join('tasks', 'tasks.id', '=', 'time_entries.task_id')
            ->join('projects', 'projects.id', '=', 'tasks.project_id')
            ->where('projects.company_id', $companyId)
            ->whereNotNull('time_entries.ended_at')
            ->whereBetween('time_entries.started_at', [$start, $end])
            ->groupBy('projects.id', 'projects.name', 'projects.budget', 'projects.hourly_rate')
            ->orderByDesc('total_minutes')
            ->get();

        return $hoursByProject->map(function ($row) {
            $hours = ($row->total_minutes ?? 0) / 60;
            $billable = $row->hourly_rate ? $hours * (float) $row->hourly_rate : 0;
            $budget = (float) ($row->budget ?? 0);

            return [
                'id' => $row->id,
                'name' => $row->name,
                'hours' => round($hours, 2),
                'hours_formatted' => minutesToHours((int) $row->total_minutes),
                'hourly_rate' => $row->hourly_rate,
                'billable' => $billable,
                'budget' => $budget,
                'formatted_billable' => $this->formatMoney($billable),
                'formatted_budget' => $budget > 0 ? $this->formatMoney($budget) : '-',
            ];
        })->all();
    }

    public function getCashFlow(int $companyId, string $month): array
    {
        $financial = $this->getMonthlyFinancial($companyId, $month);
        $date = Carbon::createFromFormat('Y-m', $month)->locale('pt_BR');

        return [
            'month_label' => ucfirst($date->translatedFormat('F')).' de '.$date->year,
            'inflows' => [
                ['label' => 'Faturamento (NF)', 'amount' => $financial['total_revenue']],
                ['label' => 'Recebíveis recebidos', 'amount' => $financial['total_receivable_paid']],
            ],
            'outflows' => [
                ['label' => 'Tributos e guias pagos', 'amount' => $financial['total_das_paid']],
                ['label' => 'Despesas operacionais pagas', 'amount' => $financial['total_payable_paid']],
            ],
            'pending_in' => $financial['total_receivable_pending'] + $financial['pending_invoices_amount'],
            'pending_out' => $financial['total_pending_out'],
            'balance' => $financial['balance'],
        ];
    }

    public function getTrendChartData(int $companyId, string $endMonth, int $months = 6): array
    {
        $end = Carbon::createFromFormat('Y-m', $endMonth)->startOfMonth();
        $labels = [];
        $revenue = [];
        $expenses = [];
        $balance = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = $end->copy()->subMonths($i);
            $key = $month->format('Y-m');
            $data = $this->getMonthlyFinancial($companyId, $key);

            $labels[] = $month->locale('pt_BR')->translatedFormat('M/y');
            $revenue[] = round((float) $data['total_revenue'] + (float) $data['total_receivable_paid'], 2);
            $expenses[] = round((float) $data['total_expenses'], 2);
            $balance[] = round((float) $data['balance'], 2);
        }

        return compact('labels', 'revenue', 'expenses', 'balance');
    }

    public function getCashFlowChartData(int $companyId, string $month): array
    {
        $cashFlow = $this->getCashFlow($companyId, $month);
        $inLabels = array_column($cashFlow['inflows'], 'label');
        $outLabels = array_column($cashFlow['outflows'], 'label');
        $inAmounts = array_column($cashFlow['inflows'], 'amount');
        $outAmounts = array_column($cashFlow['outflows'], 'amount');

        return [
            'labels' => array_merge($inLabels, $outLabels),
            'in' => array_merge($inAmounts, array_fill(0, count($outLabels), 0)),
            'out' => array_merge(array_fill(0, count($inLabels), 0), $outAmounts),
        ];
    }

    public function getCompositionChartData(int $companyId, string $month): array
    {
        $data = $this->getMonthlyFinancial($companyId, $month);

        return [
            'labels' => ['Faturamento', 'Custos pagos', 'Obrigações pendentes', 'Recebíveis em aberto'],
            'values' => [
                round((float) $data['total_revenue'], 2),
                round((float) $data['total_expenses'], 2),
                round((float) $data['total_pending_out'], 2),
                round((float) $data['total_receivable_pending'] + (float) $data['pending_invoices_amount'], 2),
            ],
        ];
    }

    private function formatMoney(float $value): string
    {
        return 'R$ '.number_format($value, 2, ',', '.');
    }
}
