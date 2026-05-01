<?php

namespace Database\Seeders;

use App\Enums\DailyPointStatus;
use App\Models\DailyPoint;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DailyPointSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            return;
        }

        // Criar pontos para todos os dias úteis de Janeiro de 2026
        // Janeiro 2026: dia 1 é quinta (feriado Ano Novo), dia 31 é sábado
        $startDate = Carbon::create(2026, 1, 1);
        $endDate = Carbon::create(2026, 1, 31);

        $date = $startDate->copy();

        while ($date->lte($endDate)) {
            // Pular fins de semana
            if ($date->isWeekend()) {
                $date->addDay();
                continue;
            }

            // Pular feriado de Ano Novo (01/01)
            if ($date->day === 1 && $date->month === 1) {
                $date->addDay();
                continue;
            }

            $baseDate = $date->copy();
            
            // Horário fixo: 08:00 - 12:00 / 13:00 - 17:00 (8 horas por dia)
            $entry = $baseDate->copy()->setTime(8, 0);
            $lunchOut = $baseDate->copy()->setTime(12, 0);
            $lunchReturn = $baseDate->copy()->setTime(13, 0);
            $exit = $baseDate->copy()->setTime(17, 0);

            $dailyPoint = new DailyPoint([
                'user_id' => $user->id,
                'work_date' => $baseDate->format('Y-m-d'),
                'entry_time' => $entry,
                'lunch_out_time' => $lunchOut,
                'lunch_return_time' => $lunchReturn,
                'exit_time' => $exit,
                'extra_start_time' => null,
                'extra_end_time' => null,
                'status' => DailyPointStatus::APPROVED,
                'notes' => null,
            ]);

            $dailyPoint->calculateMinutes();
            $dailyPoint->save();

            $date->addDay();
        }
    }
}
