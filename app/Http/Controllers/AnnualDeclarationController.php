<?php

namespace App\Http\Controllers;

use App\Models\AnnualDeclaration;
use App\Services\AnnualDeclarationService;
use App\Services\PdfService;
use App\Support\CurrentCompany;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnnualDeclarationController extends Controller
{
    public function __construct(
        private AnnualDeclarationService $annualDeclarationService,
        private PdfService $pdfService
    ) {}

    public function index()
    {
        abort_unless(CurrentCompany::canViewFinance(), 403);

        $declarations = AnnualDeclaration::forCurrentCompany()
            ->orderByDesc('reference_year')
            ->paginate(20);

        return view('annual-declarations.index', compact('declarations'));
    }

    public function generate(Request $request)
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        $year = (int) $request->get('year', Carbon::now()->year);

        try {
            $declaration = $this->annualDeclarationService->generate(
                auth()->id(),
                $year,
                CurrentCompany::id()
            );

            return redirect()->route('declaracao-anual.show', $declaration)
                ->with('success', 'Declaração anual gerada com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(AnnualDeclaration $annualDeclaration)
    {
        abort_unless(CurrentCompany::canViewFinance(), 403);

        $invoices = $annualDeclaration->invoices;
        $dasPayments = $annualDeclaration->dasPayments;

        return view('annual-declarations.show', compact('annualDeclaration', 'invoices', 'dasPayments'));
    }

    public function pdf(AnnualDeclaration $annualDeclaration)
    {
        abort_unless(CurrentCompany::canViewFinance(), 403);

        return $this->pdfService->generateAnnualDeclaration($annualDeclaration);
    }
}
