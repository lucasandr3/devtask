<?php

namespace App\Http\Controllers;

use App\Enums\InvoicePaymentStatus;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Client;
use App\Models\Invoice;
use App\Services\InvoiceXmlParserService;
use App\Support\CurrentCompany;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(CurrentCompany::canViewFinance(), 403);

        $query = Invoice::forCurrentCompany()->with(['client', 'project']);

        if ($request->filled('numero')) {
            $query->where('numero', 'like', '%'.$request->numero.'%');
        }

        if ($request->filled('data_inicio')) {
            $query->where('data_emissao', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->where('data_emissao', '<=', $request->data_fim);
        }

        $invoices = $query->orderByDesc('data_emissao')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        $clients = Client::forCurrentCompany()->orderBy('name')->get();
        $projects = CurrentCompany::projectsQuery()->orderBy('name')->get();

        return view('invoices.create', compact('clients', 'projects'));
    }

    public function importXml(Request $request, InvoiceXmlParserService $parser)
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        $request->validate([
            'xml' => ['required', 'file', 'mimes:xml', 'mimetypes:text/xml,application/xml', 'max:5120'],
        ]);

        try {
            $content = $request->file('xml')->get();
            $data = $parser->parse($content);

            return response()->json([
                'message' => 'XML importado com sucesso.',
                'data' => $data,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => collect($e->errors())->flatten()->first() ?? 'XML inválido.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function store(StoreInvoiceRequest $request)
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        $data = $this->invoicePayload($request);
        $data['user_id'] = auth()->id();
        $data['company_id'] = CurrentCompany::id();

        if ($request->hasFile('arquivo')) {
            $data['arquivo'] = $this->storePdf($request);
        }

        Invoice::create($data);

        return redirect()->route('notas-fiscais.index')
            ->with('success', 'Nota fiscal cadastrada com sucesso!');
    }

    public function show(Invoice $invoice)
    {
        abort_unless(CurrentCompany::canViewFinance(), 403);

        $invoice->load(['client', 'project']);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        $clients = Client::forCurrentCompany()->orderBy('name')->get();
        $projects = CurrentCompany::projectsQuery()->orderBy('name')->get();

        return view('invoices.edit', compact('invoice', 'clients', 'projects'));
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        $data = $this->invoicePayload($request);

        if ($request->hasFile('arquivo')) {
            if ($invoice->arquivo && Storage::exists('public/'.$invoice->arquivo)) {
                Storage::delete('public/'.$invoice->arquivo);
            }
            $data['arquivo'] = $this->storePdf($request);
        }

        $invoice->update($data);

        return redirect()->route('notas-fiscais.index')
            ->with('success', 'Nota fiscal atualizada com sucesso!');
    }

    public function destroy(Invoice $invoice)
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        if ($invoice->arquivo && Storage::exists('public/'.$invoice->arquivo)) {
            Storage::delete('public/'.$invoice->arquivo);
        }

        $invoice->delete();

        return redirect()->route('notas-fiscais.index')
            ->with('success', 'Nota fiscal excluída com sucesso!');
    }

    public function download(Invoice $invoice)
    {
        abort_unless(CurrentCompany::canViewFinance(), 403);

        if (! $invoice->arquivo || ! Storage::exists('public/'.$invoice->arquivo)) {
            return redirect()->route('notas-fiscais.index')
                ->with('error', 'Arquivo não encontrado.');
        }

        return Storage::download('public/'.$invoice->arquivo, 'nota-fiscal-'.$invoice->numero.'.pdf');
    }

    public function view(Invoice $invoice)
    {
        abort_unless(CurrentCompany::canViewFinance(), 403);

        if (! $invoice->arquivo || ! Storage::exists('public/'.$invoice->arquivo)) {
            return redirect()->route('notas-fiscais.index')
                ->with('error', 'Arquivo não encontrado.');
        }

        return response()->file(Storage::path('public/'.$invoice->arquivo), [
            'Content-Type' => 'application/pdf',
        ]);
    }

    private function invoicePayload(StoreInvoiceRequest|UpdateInvoiceRequest $request): array
    {
        return [
            'numero' => $request->numero,
            'serie' => $request->serie ?? '1',
            'data_emissao' => Carbon::parse($request->data_emissao),
            'valor' => $request->valor,
            'descricao' => $request->descricao,
            'service_code' => $request->service_code,
            'iss_value' => $request->iss_value,
            'tax_amount' => $request->tax_amount,
            'invoice_type' => $request->invoice_type ?? 'service',
            'payment_status' => $request->payment_status ?? InvoicePaymentStatus::RECEIVED->value,
            'client_id' => $request->client_id ?: null,
            'project_id' => $request->project_id ?: null,
        ];
    }

    private function storePdf(StoreInvoiceRequest|UpdateInvoiceRequest $request): string
    {
        $file = $request->file('arquivo');
        $fileName = 'invoices/'.CurrentCompany::id().'/'.time().'_'.$file->getClientOriginalName();
        $file->storeAs('public', $fileName);

        return $fileName;
    }
}
