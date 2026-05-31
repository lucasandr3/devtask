<?php

namespace App\Http\Controllers;

use App\Enums\CompanyRole;
use App\Http\Requests\StoreTeamMemberRequest;
use App\Http\Requests\UpdateTeamMemberRequest;
use App\Models\User;
use App\Support\CurrentCompany;
use Illuminate\Support\Facades\Hash;

class TeamController extends Controller
{
    public function index()
    {
        abort_unless(CurrentCompany::canManageTeam(), 403);

        $company = CurrentCompany::get();

        $members = $company?->users()
            ->orderBy('name')
            ->get() ?? collect();

        return view('team.index', compact('members', 'company'));
    }

    public function create()
    {
        abort_unless(CurrentCompany::canManageTeam(), 403);

        return view('team.create');
    }

    public function store(StoreTeamMemberRequest $request)
    {
        abort_unless(CurrentCompany::canManageTeam(), 403);

        $company = CurrentCompany::get();

        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
                'current_company_id' => $company->id,
            ]
        );

        if (!$user->wasRecentlyCreated) {
            $user->update(['current_company_id' => $company->id]);
        }

        $company->users()->syncWithoutDetaching([
            $user->id => ['role' => $request->role],
        ]);

        return redirect()->route('equipe.index')
            ->with('success', 'Membro adicionado à equipe!');
    }

    public function edit(User $user)
    {
        abort_unless(CurrentCompany::canManageTeam(), 403);

        $company = CurrentCompany::get();

        $member = $company->users()
            ->where('users.id', $user->id)
            ->firstOrFail();

        return view('team.edit', compact('member', 'company'));
    }

    public function update(UpdateTeamMemberRequest $request, User $user)
    {
        abort_unless(CurrentCompany::canManageTeam(), 403);

        $company = CurrentCompany::get();

        abort_unless($company->users()->where('users.id', $user->id)->exists(), 404);

        if ($user->id === auth()->id() && $request->role !== CompanyRole::ADMIN->value) {
            return back()->withErrors(['role' => 'Você não pode remover seu próprio papel de administrador.']);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        $company->users()->updateExistingPivot($user->id, [
            'role' => $request->role,
        ]);

        return redirect()->route('equipe.index')
            ->with('success', 'Membro atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        abort_unless(CurrentCompany::canManageTeam(), 403);

        if ($user->id === auth()->id()) {
            return back()->withErrors(['member' => 'Você não pode remover a si mesmo da equipe.']);
        }

        $company = CurrentCompany::get();
        abort_unless($company->users()->where('users.id', $user->id)->exists(), 404);

        $company->users()->detach($user->id);

        if ($user->current_company_id === $company->id) {
            $user->update(['current_company_id' => null]);
        }

        return redirect()->route('equipe.index')
            ->with('success', 'Membro removido da equipe.');
    }
}
