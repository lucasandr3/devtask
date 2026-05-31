<?php

namespace App\Services;

use App\Enums\DasPaymentStatus;
use App\Models\Invoice;
use App\Models\DasPayment;
use Carbon\Carbon;

class FinancialReportService
{
    public function getMonthlyFinancial(int $userId, string $month): array
    {
        $date = Carbon::createFromFormat('Y-m', $month);
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $invoices = Invoice::where('user_id', $userId)
            ->whereBetween('data_emissao', [$startOfMonth, $endOfMonth])
            ->get();

        $dasPayments = DasPayment::where('user_id', $userId)
            ->whereBetween('reference_month', [$startOfMonth, $endOfMonth])
            ->get();

        return $this->buildSummary($invoices, $dasPayments);
    }

    public function getMonthlyFinancialForCompany(int $companyId, string $month): array
    {
        $date = Carbon::createFromFormat('Y-m', $month);
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $invoices = Invoice::where('company_id', $companyId)
            ->whereBetween('data_emissao', [$startOfMonth, $endOfMonth])
            ->with(['client', 'project'])
            ->orderByDesc('data_emissao')
            ->get();

        $dasPayments = DasPayment::where('company_id', $companyId)
            ->whereBetween('reference_month', [$startOfMonth, $endOfMonth])
            ->orderByDesc('reference_month')
            ->get();

        return $this->buildSummary($invoices, $dasPayments);
    }

    private function buildSummary($invoices, $dasPayments): array
    {
        $totalRevenue = $invoices->sum('valor');
        $totalDasPaid = $dasPayments->where('status', DasPaymentStatus::PAID)->sum('amount');
        $totalDasPending = $dasPayments->where('status', DasPaymentStatus::PENDING)->sum('amount');
        $totalDasOverdue = $dasPayments->where('status', DasPaymentStatus::OVERDUE)->sum('amount');
        $balance = $totalRevenue - $totalDasPaid;

        return [
            'invoices' => $invoices,
            'das_payments' => $dasPayments,
            'total_revenue' => $totalRevenue,
            'total_das_paid' => $totalDasPaid,
            'total_das_pending' => $totalDasPending,
            'total_das_overdue' => $totalDasOverdue,
            'balance' => $balance,
            'formatted_total_revenue' => $this->formatMoney($totalRevenue),
            'formatted_total_das_paid' => $this->formatMoney($totalDasPaid),
            'formatted_total_das_pending' => $this->formatMoney($totalDasPending),
            'formatted_total_das_overdue' => $this->formatMoney($totalDasOverdue),
            'formatted_balance' => $this->formatMoney($balance),
        ];
    }

    private function formatMoney(float $value): string
    {
        return 'R$ '.number_format($value, 2, ',', '.');
    }
}
