<?php

namespace App\Policies;

use App\Models\MonthlyReport;
use App\Models\User;

class MonthlyReportPolicy
{
    public function approve(User $user, MonthlyReport $monthlyReport): bool
    {
        return true; // Sistema pessoal, permite aprovação
    }

    public function reject(User $user, MonthlyReport $monthlyReport): bool
    {
        return true; // Sistema pessoal, permite rejeição
    }
}
