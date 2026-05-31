<?php

namespace App\Enums;

use Carbon\Carbon;

enum InstallmentInterval: string
{
    case MONTHLY = 'monthly';
    case WEEKLY = 'weekly';
    case BIWEEKLY = 'biweekly';

    public function label(): string
    {
        return match ($this) {
            self::MONTHLY => 'Mensal',
            self::WEEKLY => 'Semanal',
            self::BIWEEKLY => 'Quinzenal',
        };
    }

    public function addTo(Carbon $date, int $step): Carbon
    {
        return match ($this) {
            self::MONTHLY => $date->copy()->addMonthsNoOverflow($step),
            self::WEEKLY => $date->copy()->addWeeks($step),
            self::BIWEEKLY => $date->copy()->addWeeks($step * 2),
        };
    }
}
