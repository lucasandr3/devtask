<?php

namespace App\Http\Controllers;

use App\Services\CompanyFinancialService;
use App\Services\FinancialAlertService;
use App\Support\CurrentCompany;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompanyFinancialController extends Controller
{
    public function __construct(
        private CompanyFinancialService $companyFinancialService,
        private FinancialAlertService $financialAlertService,
    ) {}

    public function index(Request $request)
    {
        abort_unless(CurrentCompany::canViewFinance(), 403);

        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $companyId = CurrentCompany::id();

        $financialData = $this->companyFinancialService->getMonthlyFinancial($companyId, $month);
        $projectMargins = $this->companyFinancialService->getProjectMargins($companyId, $month);
        $cashFlow = $this->companyFinancialService->getCashFlow($companyId, $month);
        $period = Carbon::createFromFormat('Y-m', $month)->locale('pt_BR');
        $monthLabel = ucfirst($period->translatedFormat('F')).' de '.$period->year;
        $alerts = $this->financialAlertService->collect($companyId);
        $trendChart = $this->companyFinancialService->getTrendChartData($companyId, $month);
        $compositionChart = $this->companyFinancialService->getCompositionChartData($companyId, $month);
        $cashFlowChart = $this->companyFinancialService->getCashFlowChartData($companyId, $month);

        return view('finance.index', compact(
            'financialData',
            'projectMargins',
            'cashFlow',
            'month',
            'monthLabel',
            'alerts',
            'trendChart',
            'compositionChart',
            'cashFlowChart',
        ));
    }
}
