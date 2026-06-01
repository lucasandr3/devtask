<?php

namespace App\Services;

use App\Enums\MonthlyReportStatus;
use App\Models\MonthlyReport;
use Carbon\Carbon;

class MonthlyReportService
{
    public function __construct(
        private WorkContractService $workContractService,
        private DailyPointService $dailyPointService
    ) {}

    public function generate(int $userId, string $month): MonthlyReport
    {
        $date = Carbon::createFromFormat('Y-m', $month);
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        // Contrato ativo no mês (meio do mês como referência) ou carga padrão de 168h
        $referenceDate = $date->copy()->day(15);
        $contractMinutes = $this->workContractService->getMonthlyMinutesForDate($userId, $referenceDate);

        // Soma horas diárias do mês
        $points = $this->dailyPointService->getMonthlyPoints($userId, $month);
        
        $normalMinutes = $points->sum('normal_minutes');
        $extraMinutes = $points->sum('extra_minutes');
        $totalMinutes = $normalMinutes + $extraMinutes;

        // Calcula saldo (total - contrato)
        $balanceMinutes = $totalMinutes - $contractMinutes;

        // Cria ou atualiza relatório
        $report = MonthlyReport::updateOrCreate(
            [
                'user_id' => $userId,
                'reference_month' => $startOfMonth,
            ],
            [
                'contract_minutes' => $contractMinutes,
                'normal_minutes' => $normalMinutes,
                'extra_minutes' => $extraMinutes,
                'total_minutes' => $totalMinutes,
                'balance_minutes' => $balanceMinutes,
                'status' => MonthlyReportStatus::DRAFT,
            ]
        );

        return $report;
    }

    public function sendForApproval(MonthlyReport $report): void
    {
        if ($report->status !== MonthlyReportStatus::DRAFT) {
            throw new \Exception('Apenas relatórios em rascunho podem ser enviados para aprovação.');
        }

        $report->status = MonthlyReportStatus::SENT;
        $report->save();
    }

    public function approve(MonthlyReport $report, string $approverName): void
    {
        if ($report->status !== MonthlyReportStatus::SENT) {
            throw new \Exception('Apenas relatórios enviados podem ser aprovados.');
        }

        $report->status = MonthlyReportStatus::APPROVED;
        $report->approver_name = $approverName;
        $report->save();
    }

    public function reject(MonthlyReport $report, ?string $notes = null): void
    {
        if ($report->status !== MonthlyReportStatus::SENT) {
            throw new \Exception('Apenas relatórios enviados podem ser rejeitados.');
        }

        $report->status = MonthlyReportStatus::REJECTED;
        if ($notes) {
            $report->notes = $notes;
        }
        $report->save();
    }
}
