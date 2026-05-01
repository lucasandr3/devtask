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

        // Busca invoices do mês
        $invoices = Invoice::where('user_id', $userId)
            ->whereBetween('data_emissao', [$startOfMonth, $endOfMonth])
            ->get();

        // Calcula receitas
        $totalRevenue = $invoices->sum('valor');

        // Busca DAS do mês
        $dasPayments = DasPayment::where('user_id', $userId)
            ->whereBetween('reference_month', [$startOfMonth, $endOfMonth])
            ->get();

        // Calcula despesas (DAS pagos)
        $totalDasPaid = $dasPayments->where('status', DasPaymentStatus::PAID)->sum('amount');
        
        // DAS pendentes
        $totalDasPending = $dasPayments->where('status', DasPaymentStatus::PENDING)->sum('amount');
        
        // DAS vencidos
        $totalDasOverdue = $dasPayments->where('status', DasPaymentStatus::OVERDUE)->sum('amount');

        // Saldo financeiro (receitas - despesas pagas)
        $balance = $totalRevenue - $totalDasPaid;

        return [
            'invoices' => $invoices,
            'das_payments' => $dasPayments,
            'total_revenue' => $totalRevenue,
            'total_das_paid' => $totalDasPaid,
            'total_das_pending' => $totalDasPending,
            'total_das_overdue' => $totalDasOverdue,
            'balance' => $balance,
            'formatted_total_revenue' => 'R$ ' . number_format($totalRevenue, 2, ',', '.'),
            'formatted_total_das_paid' => 'R$ ' . number_format($totalDasPaid, 2, ',', '.'),
            'formatted_total_das_pending' => 'R$ ' . number_format($totalDasPending, 2, ',', '.'),
            'formatted_total_das_overdue' => 'R$ ' . number_format($totalDasOverdue, 2, ',', '.'),
            'formatted_balance' => 'R$ ' . number_format($balance, 2, ',', '.'),
        ];
    }
}
