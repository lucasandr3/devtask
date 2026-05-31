<?php

namespace App\Policies;

use App\Models\MonthlyReport;
use App\Models\User;
use App\Support\CurrentCompany;

class MonthlyReportPolicy
{
    public function approve(User $user, MonthlyReport $monthlyReport): bool
    {
        if (!CurrentCompany::canApproveReports()) {
            return false;
        }

        return $monthlyReport->user->companies()
            ->where('companies.id', CurrentCompany::id())
            ->exists();
    }

    public function reject(User $user, MonthlyReport $monthlyReport): bool
    {
        return $this->approve($user, $monthlyReport);
    }

    public function view(User $user, MonthlyReport $monthlyReport): bool
    {
        if ($monthlyReport->user_id === $user->id) {
            return true;
        }

        return $this->approve($user, $monthlyReport);
    }
}
