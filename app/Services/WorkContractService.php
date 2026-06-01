<?php

namespace App\Services;

use App\Models\UserWorkContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WorkContractService
{
    /** Carga horária mensal padrão (168h) quando não há contrato ativo. */
    public const DEFAULT_MONTHLY_MINUTES = 168 * 60;

    public function getMonthlyMinutesForDate(int $userId, Carbon $date): int
    {
        $contract = $this->getActiveContractForDate($userId, $date);

        return $contract?->monthly_minutes ?? self::DEFAULT_MONTHLY_MINUTES;
    }

    public function getActiveContractForDate(int $userId, Carbon $date): ?UserWorkContract
    {
        return UserWorkContract::where('user_id', $userId)
            ->active($date)
            ->orderBy('start_date', 'desc')
            ->first();
    }

    public function validateNoOverlap(int $userId, Carbon $startDate, ?Carbon $endDate = null): bool
    {
        $query = UserWorkContract::where('user_id', $userId)
            ->where(function ($q) use ($startDate, $endDate) {
                // Contratos que começam antes ou no mesmo dia do fim do novo contrato
                $q->where('start_date', '<=', $endDate ?? Carbon::now()->addYears(100))
                  ->where(function ($subQ) use ($startDate) {
                      // E terminam depois do início do novo contrato (ou não terminam)
                      $subQ->whereNull('end_date')
                           ->orWhere('end_date', '>=', $startDate);
                  });
            });

        return $query->count() === 0;
    }
}
