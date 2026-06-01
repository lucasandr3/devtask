<?php

namespace App\Http\Controllers;

use App\Enums\SiteLeadStatus;
use App\Models\SiteLead;
use App\Support\CurrentCompany;
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

        $newCount = SiteLead::forCurrentCompany()
            ->where('status', SiteLeadStatus::NEW)
            ->count();

        return view('site-leads.index', compact('leads', 'newCount', 'status'));
    }

    public function show(SiteLead $siteLead)
    {
        abort_unless(CurrentCompany::canManageTeam(), 403);

        $siteLead->markAsRead();

        return view('site-leads.show', compact('siteLead'));
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
