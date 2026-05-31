<?php

namespace App\Support;

use App\Enums\CompanyRole;
use App\Models\Company;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class CurrentCompany
{
    public static function get(): ?Company
    {
        $user = auth()->user();

        if (!$user?->current_company_id) {
            return null;
        }

        return Company::find($user->current_company_id);
    }

    public static function id(): ?int
    {
        return auth()->user()?->current_company_id;
    }

    public static function role(User $user = null): ?CompanyRole
    {
        $user ??= auth()->user();
        $companyId = self::id();

        if (!$user || !$companyId) {
            return null;
        }

        $pivot = $user->companies()
            ->where('companies.id', $companyId)
            ->first()
            ?->pivot;

        if (!$pivot?->role) {
            return null;
        }

        return CompanyRole::tryFrom($pivot->role);
    }

    public static function canManageProjects(): bool
    {
        return self::role()?->canManageProjects() ?? false;
    }

    public static function canManageTeam(): bool
    {
        return self::role()?->canManageTeam() ?? false;
    }

    public static function canViewCompanyReports(): bool
    {
        return self::role()?->canViewCompanyReports() ?? false;
    }

    public static function canViewFinance(): bool
    {
        return self::role()?->canViewFinance() ?? false;
    }

    public static function canManageFinance(): bool
    {
        return self::role()?->canManageFinance() ?? false;
    }

    public static function canApproveReports(): bool
    {
        return self::role()?->canApproveReports() ?? false;
    }

    public static function isMember(): bool
    {
        return self::role()?->isMember() ?? false;
    }

    public static function projectsQuery(): Builder
    {
        $query = Project::where('company_id', self::id());

        if (self::isMember()) {
            $query->whereHas('tasks', fn ($q) => $q->where('assigned_to', auth()->id()));
        }

        return $query;
    }

    public static function tasksQuery(): Builder
    {
        $query = Task::whereHas('project', fn ($q) => $q->where('company_id', self::id()));

        if (self::isMember()) {
            $query->where('assigned_to', auth()->id());
        }

        return $query;
    }
}
