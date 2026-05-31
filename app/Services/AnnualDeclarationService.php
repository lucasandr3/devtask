<?php

namespace App\Services;

use App\Models\AnnualDeclaration;
use App\Models\Invoice;
use App\Models\DasPayment;
use Carbon\Carbon;

class AnnualDeclarationService
{
    public function generate(int $userId, int $year, ?int $companyId = null): AnnualDeclaration
    {
        $startOfYear = Carbon::create($year, 1, 1)->startOfYear();
        $endOfYear = Carbon::create($year, 12, 31)->endOfYear();

        $invoiceQuery = Invoice::query()->whereBetween('data_emissao', [$startOfYear, $endOfYear]);
        $dasQuery = DasPayment::query()
            ->whereYear('reference_month', $year)
            ->where('status', 'paid');

        if ($companyId) {
            $invoiceQuery->where('company_id', $companyId);
            $dasQuery->where('company_id', $companyId);
        } else {
            $invoiceQuery->where('user_id', $userId);
            $dasQuery->where('user_id', $userId);
        }

        $invoices = $invoiceQuery->get();
        $totalRevenue = $invoices->sum('valor');
        $totalDasPaid = $dasQuery->get()->sum('amount');

        $keys = ['reference_year' => $year];
        $attrs = [
            'total_revenue' => $totalRevenue,
            'total_das_paid' => $totalDasPaid,
            'total_invoices' => $invoices->count(),
            'generated_at' => Carbon::now(),
        ];

        if ($companyId) {
            $keys['company_id'] = $companyId;
            $attrs['user_id'] = $userId;
        } else {
            $keys['user_id'] = $userId;
        }

        return AnnualDeclaration::updateOrCreate($keys, $attrs);
    }
}
