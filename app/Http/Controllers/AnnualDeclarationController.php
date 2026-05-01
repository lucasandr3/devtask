<?php

namespace App\Http\Controllers;

use App\Models\AnnualDeclaration;
use App\Services\AnnualDeclarationService;
use App\Services\PdfService;
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
        $declarations = AnnualDeclaration::where('user_id', auth()->id())
            ->orderBy('reference_year', 'desc')
            ->paginate(20);

        return view('annual-declarations.index', compact('declarations'));
    }

    public function generate(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);

        try {
            $declaration = $this->annualDeclarationService->generate(auth()->id(), (int)$year);
            return redirect()->route('declaracao-anual.show', $declaration)
                ->with('success', 'Declaração anual gerada com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(AnnualDeclaration $annualDeclaration)
    {
        if ($annualDeclaration->user_id !== auth()->id()) {
            abort(403);
        }

        $invoices = $annualDeclaration->invoices;
        $dasPayments = $annualDeclaration->dasPayments;

        return view('annual-declarations.show', compact('annualDeclaration', 'invoices', 'dasPayments'));
    }

    public function pdf(AnnualDeclaration $annualDeclaration)
    {
        if ($annualDeclaration->user_id !== auth()->id()) {
            abort(403);
        }

        return $this->pdfService->generateAnnualDeclaration($annualDeclaration);
    }
}
