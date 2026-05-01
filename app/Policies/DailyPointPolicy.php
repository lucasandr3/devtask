<?php

namespace App\Policies;

use App\Models\DailyPoint;
use App\Models\User;

class DailyPointPolicy
{
    public function approve(User $user, DailyPoint $dailyPoint): bool
    {
        return true; // Sistema pessoal, permite aprovação
    }

    public function reject(User $user, DailyPoint $dailyPoint): bool
    {
        return true; // Sistema pessoal, permite rejeição
    }
}
