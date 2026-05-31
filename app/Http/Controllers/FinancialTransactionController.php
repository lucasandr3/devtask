<?php

namespace App\Http\Controllers;

use App\Enums\FinancialTransactionStatus;
use App\Enums\FinancialTransactionType;
use App\Enums\InstallmentInterval;
use App\Models\FinancialTransaction;
use App\Services\FinancialInstallmentService;
use App\Support\CurrentCompany;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FinancialTransactionController extends Controller
{
    public function __construct(
        private FinancialInstallmentService $installmentService
    ) {}

    public function index(Request $request)
    {
        abort_unless(CurrentCompany::canViewFinance(), 403);

        $query = FinancialTransaction::forCurrentCompany()
            ->with(['client', 'project'])
            ->orderByDesc('due_date')
            ->orderByDesc('created_at');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->paginate(20)->withQueryString();

        return view('financial-transactions.index', compact('transactions'));
    }

    public function create(Request $request)
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        $type = $request->get('type', FinancialTransactionType::PAYABLE->value);
        $clients = CurrentCompany::get()->clients()->orderBy('name')->get();
        $projects = CurrentCompany::projectsQuery()->orderBy('name')->get();

        return view('financial-transactions.create', compact('type', 'clients', 'projects'));
    }

    public function store(Request $request)
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        $validated = $this->validateTransaction($request);
        $companyId = CurrentCompany::id();

        if (($validated['payment_mode'] ?? 'single') === 'installment') {
            $created = $this->installmentService->createInstallments([
                'company_id' => $companyId,
                'user_id' => auth()->id(),
                'client_id' => $validated['client_id'] ?? null,
                'project_id' => $validated['project_id'] ?? null,
                'type' => $validated['type'],
                'description' => $validated['description'],
                'amount' => $validated['amount'],
                'due_date' => $validated['due_date'],
                'installment_count' => $validated['installment_count'],
                'installment_interval' => $validated['installment_interval'],
                'category' => $validated['category'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            return redirect()->route('financeiro.lancamentos.index')
                ->with('success', $created->count().' parcelas cadastradas com sucesso!');
        }

        $paidAt = ! empty($validated['paid_at']) ? Carbon::parse($validated['paid_at']) : null;

        FinancialTransaction::create([
            'company_id' => $companyId,
            'user_id' => auth()->id(),
            'client_id' => $validated['client_id'] ?? null,
            'project_id' => $validated['project_id'] ?? null,
            'type' => $validated['type'],
            'status' => $paidAt ? FinancialTransactionStatus::PAID : FinancialTransactionStatus::PENDING,
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'due_date' => Carbon::parse($validated['due_date']),
            'paid_at' => $paidAt,
            'category' => $validated['category'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'installment_number' => 1,
            'installment_count' => 1,
        ]);

        return redirect()->route('financeiro.lancamentos.index')
            ->with('success', 'Lançamento cadastrado com sucesso!');
    }

    public function edit(FinancialTransaction $lancamento)
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        $clients = CurrentCompany::get()->clients()->orderBy('name')->get();
        $projects = CurrentCompany::projectsQuery()->orderBy('name')->get();
        $siblings = $this->installmentService->siblings($lancamento);

        return view('financial-transactions.edit', [
            'transaction' => $lancamento,
            'clients' => $clients,
            'projects' => $projects,
            'siblings' => $siblings,
        ]);
    }

    public function update(Request $request, FinancialTransaction $lancamento)
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        $validated = $this->validateTransaction($request, editing: true);
        $paidAt = ! empty($validated['paid_at']) ? Carbon::parse($validated['paid_at']) : null;

        $lancamento->update([
            'client_id' => $validated['client_id'] ?? null,
            'project_id' => $validated['project_id'] ?? null,
            'type' => $validated['type'],
            'status' => $paidAt ? FinancialTransactionStatus::PAID : FinancialTransactionStatus::PENDING,
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'due_date' => Carbon::parse($validated['due_date']),
            'paid_at' => $paidAt,
            'category' => $validated['category'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('financeiro.lancamentos.index')
            ->with('success', 'Lançamento atualizado com sucesso!');
    }

    public function destroy(Request $request, FinancialTransaction $lancamento)
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        if ($request->boolean('delete_group') && $lancamento->installment_group_id) {
            $count = $this->installmentService->deleteGroup(
                $lancamento->installment_group_id,
                CurrentCompany::id()
            );

            return redirect()->route('financeiro.lancamentos.index')
                ->with('success', "Grupo de {$count} parcelas removido com sucesso!");
        }

        $lancamento->delete();

        return redirect()->route('financeiro.lancamentos.index')
            ->with('success', 'Lançamento removido com sucesso!');
    }

    private function validateTransaction(Request $request, bool $editing = false): array
    {
        if ($request->has('amount')) {
            $parsed = parse_brazilian_money($request->input('amount'));
            if ($parsed !== null) {
                $request->merge(['amount' => $parsed]);
            }
        }

        $paymentMode = $request->input('payment_mode', 'single');

        $rules = [
            'type' => ['required', Rule::enum(FinancialTransactionType::class)],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'due_date' => ['required', 'date'],
            'paid_at' => ['nullable', 'date'],
            'client_id' => ['nullable', 'exists:clients,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'category' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ];

        if (! $editing) {
            $rules['payment_mode'] = ['required', Rule::in(['single', 'installment'])];
            $rules['installment_count'] = [
                Rule::requiredIf($paymentMode === 'installment'),
                'nullable',
                'integer',
                'min:2',
                'max:360',
            ];
            $rules['installment_interval'] = [
                Rule::requiredIf($paymentMode === 'installment'),
                'nullable',
                Rule::enum(InstallmentInterval::class),
            ];
            if ($paymentMode === 'installment') {
                $rules['paid_at'] = ['prohibited'];
            }
        }

        return $request->validate($rules);
    }
}
