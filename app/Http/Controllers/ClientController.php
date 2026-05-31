<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Support\CurrentCompany;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        abort_unless(CurrentCompany::canViewFinance(), 403);

        $clients = Client::forCurrentCompany()
            ->withCount(['projects', 'invoices'])
            ->orderBy('name')
            ->paginate(20);

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        return view('clients.create');
    }

    public function store(Request $request)
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'document' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'notes' => ['nullable', 'string'],
        ]);

        Client::create([
            ...$validated,
            'company_id' => CurrentCompany::id(),
        ]);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente cadastrado com sucesso!');
    }

    public function edit(Client $client)
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'document' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'notes' => ['nullable', 'string'],
        ]);

        $client->update($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente atualizado com sucesso!');
    }

    public function destroy(Client $client)
    {
        abort_unless(CurrentCompany::canManageFinance(), 403);

        $client->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente removido com sucesso!');
    }
}
