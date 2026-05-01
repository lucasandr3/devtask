<?php

namespace App\Services;

use App\Models\AnnualDeclaration;
use App\Models\Invoice;
use App\Models\DasPayment;
use Carbon\Carbon;

class AnnualDeclarationService
{
    public function generate(int $userId, int $year): AnnualDeclaration
    {
        $startOfYear = Carbon::create($year, 1, 1)->startOfYear();
        $endOfYear = Carbon::create($year, 12, 31)->endOfYear();

        // Busca todas as invoices do ano
        $invoices = Invoice::where('user_id', $userId)
            ->whereBetween('data_emissao', [$startOfYear, $endOfYear])
            ->get();

        // Calcula receita total
        $totalRevenue = $invoices->sum('valor');

        // Busca todos os DAS pagos do ano
        $dasPayments = DasPayment::where('user_id', $userId)
            ->whereYear('reference_month', $year)
            ->where('status', 'paid')
            ->get();

        // Calcula total de DAS pago
        $totalDasPaid = $dasPayments->sum('amount');

        // Cria ou atualiza declaração anual
        $declaration = AnnualDeclaration::updateOrCreate(
            [
                'user_id' => $userId,
                'reference_year' => $year,
            ],
            [
                'total_revenue' => $totalRevenue,
                'total_das_paid' => $totalDasPaid,
                'total_invoices' => $invoices->count(),
                'generated_at' => Carbon::now(),
            ]
        );

        return $declaration;
    }
}
