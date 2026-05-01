<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDasPaymentRequest;
use App\Http\Requests\UpdateDasPaymentRequest;
use App\Models\DasPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DasPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = DasPayment::where('user_id', auth()->id());

        if ($request->has('month')) {
            $date = Carbon::createFromFormat('Y-m', $request->month);
            $query->whereYear('reference_month', $date->year)
                  ->whereMonth('reference_month', $date->month);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $dasPayments = $query->orderBy('reference_month', 'desc')
            ->orderBy('due_date', 'desc')
            ->paginate(20);

        return view('das-payments.index', compact('dasPayments'));
    }

    public function create()
    {
        return view('das-payments.create');
    }

    public function store(StoreDasPaymentRequest $request)
    {
        $data = [
            'user_id' => auth()->id(),
            'reference_month' => Carbon::parse($request->reference_month),
            'due_date' => Carbon::parse($request->due_date),
            'payment_date' => $request->payment_date ? Carbon::parse($request->payment_date) : null,
            'amount' => $request->amount,
            'notes' => $request->notes,
        ];

        // Upload do arquivo de comprovante se fornecido
        if ($request->hasFile('receipt_file')) {
            $file = $request->file('receipt_file');
            $fileName = 'das-payments/' . auth()->id() . '/' . time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public', $fileName);
            $data['receipt_file'] = $fileName;
        }

        DasPayment::create($data);

        return redirect()->route('das.index')
            ->with('success', 'DAS cadastrado com sucesso!');
    }

    public function show(DasPayment $dasPayment)
    {
        if ($dasPayment->user_id !== auth()->id()) {
            abort(403);
        }

        return view('das-payments.show', compact('dasPayment'));
    }

    public function edit(DasPayment $dasPayment)
    {
        if ($dasPayment->user_id !== auth()->id()) {
            abort(403);
        }

        return view('das-payments.edit', compact('dasPayment'));
    }

    public function update(UpdateDasPaymentRequest $request, DasPayment $dasPayment)
    {
        if ($dasPayment->user_id !== auth()->id()) {
            abort(403);
        }

        $data = [
            'reference_month' => Carbon::parse($request->reference_month),
            'due_date' => Carbon::parse($request->due_date),
            'payment_date' => $request->payment_date ? Carbon::parse($request->payment_date) : null,
            'amount' => $request->amount,
            'notes' => $request->notes,
        ];

        // Upload do arquivo de comprovante se fornecido
        if ($request->hasFile('receipt_file')) {
            // Remove arquivo antigo se existir
            if ($dasPayment->receipt_file && Storage::exists('public/' . $dasPayment->receipt_file)) {
                Storage::delete('public/' . $dasPayment->receipt_file);
            }

            $file = $request->file('receipt_file');
            $fileName = 'das-payments/' . auth()->id() . '/' . time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public', $fileName);
            $data['receipt_file'] = $fileName;
        }

        $dasPayment->update($data);

        return redirect()->route('das.index')
            ->with('success', 'DAS atualizado com sucesso!');
    }

    public function destroy(DasPayment $dasPayment)
    {
        if ($dasPayment->user_id !== auth()->id()) {
            abort(403);
        }

        // Remove arquivo se existir
        if ($dasPayment->receipt_file && Storage::exists('public/' . $dasPayment->receipt_file)) {
            Storage::delete('public/' . $dasPayment->receipt_file);
        }

        $dasPayment->delete();

        return redirect()->route('das.index')
            ->with('success', 'DAS excluído com sucesso!');
    }
}
