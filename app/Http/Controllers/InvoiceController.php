<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::where('user_id', auth()->id());

        if ($request->has('numero')) {
            $query->where('numero', 'like', '%' . $request->numero . '%');
        }

        if ($request->has('data_inicio')) {
            $query->where('data_emissao', '>=', $request->data_inicio);
        }

        if ($request->has('data_fim')) {
            $query->where('data_emissao', '<=', $request->data_fim);
        }

        $invoices = $query->orderBy('data_emissao', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('invoices.create');
    }

    public function store(StoreInvoiceRequest $request)
    {
        $data = [
            'user_id' => auth()->id(),
            'numero' => $request->numero,
            'serie' => $request->serie ?? '1',
            'data_emissao' => Carbon::parse($request->data_emissao),
            'valor' => $request->valor,
            'descricao' => $request->descricao,
            'service_code' => $request->service_code,
            'iss_value' => $request->iss_value,
            'tax_amount' => $request->tax_amount,
            'invoice_type' => $request->invoice_type ?? 'service',
        ];

        // Upload do arquivo PDF se fornecido
        if ($request->hasFile('arquivo')) {
            $file = $request->file('arquivo');
            $fileName = 'invoices/' . auth()->id() . '/' . time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public', $fileName);
            $data['arquivo'] = $fileName;
        }

        Invoice::create($data);

        return redirect()->route('notas-fiscais.index')
            ->with('success', 'Nota fiscal cadastrada com sucesso!');
    }

    public function show(Invoice $invoice)
    {
        if ($invoice->user_id != auth()->id()) {
            abort(403);
        }

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        if ($invoice->user_id != auth()->id()) {
            abort(403);
        }

        return view('invoices.edit', compact('invoice'));
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        if ($invoice->user_id != auth()->id()) {
            abort(403);
        }

        $data = [
            'numero' => $request->numero,
            'serie' => $request->serie ?? '1',
            'data_emissao' => Carbon::parse($request->data_emissao),
            'valor' => $request->valor,
            'descricao' => $request->descricao,
            'service_code' => $request->service_code,
            'iss_value' => $request->iss_value,
            'tax_amount' => $request->tax_amount,
            'invoice_type' => $request->invoice_type ?? 'service',
        ];

        // Upload do arquivo PDF se fornecido
        if ($request->hasFile('arquivo')) {
            // Remove arquivo antigo se existir
            if ($invoice->arquivo && Storage::exists('public/' . $invoice->arquivo)) {
                Storage::delete('public/' . $invoice->arquivo);
            }

            $file = $request->file('arquivo');
            $fileName = 'invoices/' . auth()->id() . '/' . time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public', $fileName);
            $data['arquivo'] = $fileName;
        }

        $invoice->update($data);

        return redirect()->route('notas-fiscais.index')
            ->with('success', 'Nota fiscal atualizada com sucesso!');
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->user_id != auth()->id()) {
            abort(403);
        }

        // Remove arquivo se existir
        if ($invoice->arquivo && Storage::exists('public/' . $invoice->arquivo)) {
            Storage::delete('public/' . $invoice->arquivo);
        }

        $invoice->delete();

        return redirect()->route('notas-fiscais.index')
            ->with('success', 'Nota fiscal excluída com sucesso!');
    }

    public function download(Invoice $invoice)
    {
        if ($invoice->user_id != auth()->id()) {
            abort(403);
        }

        if (!$invoice->arquivo || !Storage::exists('public/' . $invoice->arquivo)) {
            return redirect()->route('notas-fiscais.index')
                ->with('error', 'Arquivo não encontrado.');
        }

        return Storage::download('public/' . $invoice->arquivo, 'nota-fiscal-' . $invoice->numero . '.pdf');
    }

    public function view(Invoice $invoice)
    {
        if ($invoice->user_id != auth()->id()) {
            abort(403);
        }

        if (!$invoice->arquivo || !Storage::exists('public/' . $invoice->arquivo)) {
            return redirect()->route('notas-fiscais.index')
                ->with('error', 'Arquivo não encontrado.');
        }

        $filePath = Storage::path('public/' . $invoice->arquivo);
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
