<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDasPaymentRequest;
use App\Http\Requests\UpdateDasPaymentRequest;
use App\Models\DasPayment;
use App\Support\CurrentCompany;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DasPaymentController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(CurrentCompany::canViewFinance(), 403);

        $query = DasPayment::forCurrentCompany();

        if ($request->filled('month')) {
            $date = Carbon::createFromFormat('Y-m', $request->month);
            $query->whereYear('reference_month', $date->year)
                ->whereMonth('reference_month', $date->month);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $dasPayments = $query->orderByDesc('reference_month')
            ->orderByDesc('due_date')
            ->paginate(20);

        return view('das-payments.index', compact('dasPayments'));
    }

    public function create()
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        return view('das-payments.create');
    }

    public function store(StoreDasPaymentRequest $request)
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        $data = [
            'user_id' => auth()->id(),
            'company_id' => CurrentCompany::id(),
            'reference_month' => Carbon::parse($request->reference_month),
            'due_date' => Carbon::parse($request->due_date),
            'payment_date' => $request->payment_date ? Carbon::parse($request->payment_date) : null,
            'amount' => $request->amount,
            'notes' => $request->notes,
        ];

        if ($request->hasFile('receipt_file')) {
            $data['receipt_file'] = $this->storeReceipt($request);
        }

        DasPayment::create($data);

        return redirect()->route('das.index')
            ->with('success', 'DAS cadastrado com sucesso!');
    }

    public function edit(DasPayment $dasPayment)
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        return view('das-payments.edit', compact('dasPayment'));
    }

    public function update(UpdateDasPaymentRequest $request, DasPayment $dasPayment)
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        $data = [
            'reference_month' => Carbon::parse($request->reference_month),
            'due_date' => Carbon::parse($request->due_date),
            'payment_date' => $request->payment_date ? Carbon::parse($request->payment_date) : null,
            'amount' => $request->amount,
            'notes' => $request->notes,
        ];

        if ($request->hasFile('receipt_file')) {
            if ($dasPayment->receipt_file && Storage::exists('public/'.$dasPayment->receipt_file)) {
                Storage::delete('public/'.$dasPayment->receipt_file);
            }
            $data['receipt_file'] = $this->storeReceipt($request);
        }

        $dasPayment->update($data);

        return redirect()->route('das.index')
            ->with('success', 'DAS atualizado com sucesso!');
    }

    public function destroy(DasPayment $dasPayment)
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        if ($dasPayment->receipt_file && Storage::exists('public/'.$dasPayment->receipt_file)) {
            Storage::delete('public/'.$dasPayment->receipt_file);
        }

        $dasPayment->delete();

        return redirect()->route('das.index')
            ->with('success', 'DAS excluído com sucesso!');
    }

    private function storeReceipt(StoreDasPaymentRequest|UpdateDasPaymentRequest $request): string
    {
        $file = $request->file('receipt_file');
        $fileName = 'das-payments/'.CurrentCompany::id().'/'.time().'_'.$file->getClientOriginalName();
        $file->storeAs('public', $fileName);

        return $fileName;
    }
}
