<?php

namespace Database\Seeders;

use App\Enums\MonthlyReportStatus;
use App\Models\MonthlyReport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MonthlyReportSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            return;
        }

        // Criar relatórios dos últimos 6 meses
        for ($i = 6; $i >= 0; $i--) {
            $referenceMonth = Carbon::now()->subMonths($i)->startOfMonth();
            
            // Verifica se já existe
            if (MonthlyReport::where('user_id', $user->id)
                ->whereDate('reference_month', $referenceMonth)
                ->exists()) {
                continue;
            }
            
            // Minutos de contrato (220h = 13200 min)
            $contractMinutes = 13200;
            
            // Gera valores realistas
            $normalMinutes = fake()->numberBetween(12000, 13500);
            $extraMinutes = fake()->boolean(40) ? fake()->numberBetween(0, 1200) : 0;
            $totalMinutes = $normalMinutes + $extraMinutes;
            $balanceMinutes = $totalMinutes - $contractMinutes;

            // Status baseado no mês
            $status = match(true) {
                $i === 0 => MonthlyReportStatus::DRAFT, // Mês atual
                $i === 1 => MonthlyReportStatus::SENT, // Mês passado
                default => fake()->randomElement([
                    MonthlyReportStatus::APPROVED,
                    MonthlyReportStatus::APPROVED,
                    MonthlyReportStatus::APPROVED,
                    MonthlyReportStatus::REJECTED,
                ]),
            };

            MonthlyReport::create([
                'user_id' => $user->id,
                'reference_month' => $referenceMonth,
                'contract_minutes' => $contractMinutes,
                'normal_minutes' => $normalMinutes,
                'extra_minutes' => $extraMinutes,
                'total_minutes' => $totalMinutes,
                'balance_minutes' => $balanceMinutes,
                'status' => $status,
                'approver_name' => $status === MonthlyReportStatus::APPROVED ? 'João Silva' : null,
                'notes' => $status === MonthlyReportStatus::REJECTED 
                    ? 'Favor revisar os registros do dia 15' 
                    : null,
            ]);
        }
    }
}
