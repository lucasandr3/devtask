<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserWorkContract;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WorkContractSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if ($user) {
            // Contrato atual - 168 horas mensais = 10080 minutos
            UserWorkContract::create([
                'user_id' => $user->id,
                'company_name' => 'Tech Solutions Ltda',
                'contract_value' => 9500.00,
                'monthly_minutes' => 168 * 60, // 168 horas
                'start_date' => Carbon::now()->subMonths(6)->startOfMonth(),
                'end_date' => null,
                'notes' => 'Contrato de prestação de serviços de desenvolvimento',
            ]);

            // Contrato antigo finalizado
            UserWorkContract::create([
                'user_id' => $user->id,
                'company_name' => 'Digital Apps S.A.',
                'contract_value' => 8000.00,
                'monthly_minutes' => 10560, // 176 horas
                'start_date' => Carbon::now()->subMonths(18)->startOfMonth(),
                'end_date' => Carbon::now()->subMonths(7)->endOfMonth(),
                'notes' => 'Contrato encerrado',
            ]);
        }
    }
}
