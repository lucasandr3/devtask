<?php

namespace App\Http\Controllers;

use App\Enums\SiteLeadSegment;
use App\Enums\SiteLeadStatus;
use App\Models\Client;
use App\Models\SiteLead;
use App\Support\CurrentCompany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SiteLeadController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(CurrentCompany::canManageTeam(), 403);

        $status = $request->query('status');

        $leads = SiteLead::forCurrentCompany()
            ->when($status && in_array($status, ['new', 'read', 'archived'], true), fn ($q) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $leads->load('client');

        $newCount = SiteLead::forCurrentCompany()
            ->where('status', SiteLeadStatus::NEW)
            ->count();

        return view('site-leads.index', compact('leads', 'newCount', 'status'));
    }

    public function show(SiteLead $siteLead)
    {
        abort_unless(CurrentCompany::canManageTeam(), 403);

        $siteLead->load('client');
        $siteLead->markAsRead();

        return view('site-leads.show', compact('siteLead'));
    }

    public function convertToClient(SiteLead $siteLead): RedirectResponse
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        if ($siteLead->isConverted()) {
            $siteLead->load('client');

            return redirect()
                ->route('clientes.edit', $siteLead->client)
                ->with('success', 'Este contato já está vinculado a um cliente.');
        }

        $existingClient = Client::forCurrentCompany()
            ->where('email', $siteLead->email)
            ->first();

        if ($existingClient) {
            $siteLead->update([
                'client_id' => $existingClient->id,
                'status' => SiteLeadStatus::ARCHIVED,
            ]);

            return redirect()
                ->route('clientes.edit', $existingClient)
                ->with('success', 'Já existe um cliente com este e-mail. O contato foi vinculado a ele.');
        }

        $notes = collect([
            $siteLead->company_name ? "Empresa: {$siteLead->company_name}" : null,
            $siteLead->segment ? 'Segmento: '.SiteLeadSegment::labelFor($siteLead->segment) : null,
            "Mensagem do site:\n{$siteLead->message}",
        ])->filter()->implode("\n\n");

        $client = Client::create([
            'company_id' => CurrentCompany::id(),
            'name' => $siteLead->name,
            'email' => $siteLead->email,
            'phone' => $siteLead->phone,
            'notes' => $notes,
        ]);

        $siteLead->update([
            'client_id' => $client->id,
            'status' => SiteLeadStatus::ARCHIVED,
        ]);

        return redirect()
            ->route('clientes.edit', $client)
            ->with('success', 'Cliente criado a partir do contato.');
    }

    public function archive(SiteLead $siteLead)
    {
        abort_unless(CurrentCompany::canManageTeam(), 403);

        $siteLead->update(['status' => SiteLeadStatus::ARCHIVED]);

        return redirect()
            ->route('contatos-site.index')
            ->with('success', 'Contato arquivado.');
    }

    public function destroy(SiteLead $siteLead)
    {
        abort_unless(CurrentCompany::canManageTeam(), 403);

        $siteLead->delete();

        return redirect()
            ->route('contatos-site.index')
            ->with('success', 'Contato excluído.');
    }
}
