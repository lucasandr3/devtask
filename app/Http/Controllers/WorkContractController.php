<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkContractRequest;
use App\Http\Requests\UpdateWorkContractRequest;
use App\Models\MonthlyReport;
use App\Models\UserWorkContract;
use App\Services\MonthlyReportService;
use App\Services\WorkContractService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkContractController extends Controller
{
    public function __construct(
        private WorkContractService $workContractService,
        private MonthlyReportService $monthlyReportService
    ) {}

    public function index()
    {
        $contracts = UserWorkContract::where('user_id', auth()->id())
            ->orderBy('start_date', 'desc')
            ->get();

        return view('work-contracts.index', compact('contracts'));
    }

    public function create()
    {
        return view('work-contracts.create');
    }

    public function store(StoreWorkContractRequest $request)
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : null;

        if (!$this->workContractService->validateNoOverlap(auth()->id(), $startDate, $endDate)) {
            return back()->withErrors(['start_date' => 'Já existe um contrato ativo neste período.'])->withInput();
        }

        // Converte horas para minutos
        $monthlyMinutes = (int) round($request->monthly_hours * 60);

        UserWorkContract::create([
            'user_id' => auth()->id(),
            'company_name' => $request->company_name,
            'contract_value' => $request->contract_value,
            'monthly_minutes' => $monthlyMinutes,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'notes' => $request->notes,
        ]);

        return redirect()->route('contratos.index')
            ->with('success', 'Contrato criado com sucesso!');
    }

    public function edit(UserWorkContract $workContract)
    {
        if ($workContract->user_id !== auth()->id()) {
            abort(403);
        }

        return view('work-contracts.edit', compact('workContract'));
    }

    public function update(UpdateWorkContractRequest $request, UserWorkContract $workContract)
    {
        if ($workContract->user_id !== auth()->id()) {
            abort(403);
        }

        $startDate = Carbon::parse($request->start_date);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : null;

        // Valida sobreposição excluindo o contrato atual
        $overlapping = UserWorkContract::where('user_id', auth()->id())
            ->where('id', '!=', $workContract->id)
            ->where(function ($q) use ($startDate, $endDate) {
                $q->where('start_date', '<=', $endDate ?? Carbon::now()->addYears(100))
                  ->where(function ($subQ) use ($startDate) {
                      $subQ->whereNull('end_date')
                           ->orWhere('end_date', '>=', $startDate);
                  });
            })
            ->exists();

        if ($overlapping) {
            return back()->withErrors(['start_date' => 'Já existe um contrato ativo neste período.'])->withInput();
        }

        // Converte horas para minutos
        $monthlyMinutes = (int) round($request->monthly_hours * 60);

        $oldMonthlyMinutes = $workContract->monthly_minutes;

        $workContract->update([
            'company_name' => $request->company_name,
            'contract_value' => $request->contract_value,
            'monthly_minutes' => $monthlyMinutes,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'notes' => $request->notes,
        ]);

        // Se as horas mensais mudaram, atualiza relatórios em rascunho
        if ($oldMonthlyMinutes !== $monthlyMinutes) {
            $this->updateDraftReports($workContract);
        }

        return redirect()->route('contratos.index')
            ->with('success', 'Contrato atualizado com sucesso!');
    }

    public function destroy(UserWorkContract $workContract)
    {
        if ($workContract->user_id !== auth()->id()) {
            abort(403);
        }

        $workContract->delete();

        return redirect()->route('contratos.index')
            ->with('success', 'Contrato excluído com sucesso!');
    }

    /**
     * Atualiza relatórios mensais em rascunho quando o contrato é atualizado
     */
    private function updateDraftReports(UserWorkContract $contract): void
    {
        // Busca todos os relatórios em rascunho que podem estar usando este contrato
        $reports = MonthlyReport::where('user_id', $contract->user_id)
            ->where('status', \App\Enums\MonthlyReportStatus::DRAFT)
            ->get();

        foreach ($reports as $report) {
            // Verifica se o contrato estava ativo no mês do relatório
            $monthDate = $report->reference_month->copy()->day(15);
            if ($contract->isActiveOnDate($monthDate)) {
                // Recalcula o relatório com o novo valor do contrato
                try {
                    $this->monthlyReportService->generate(
                        $contract->user_id,
                        $report->reference_month->format('Y-m')
                    );
                } catch (\Exception $e) {
                    // Ignora erros (ex: contrato não mais ativo)
                }
            }
        }
    }
}
