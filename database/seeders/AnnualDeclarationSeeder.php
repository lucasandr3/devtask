<?php

namespace Database\Seeders;

use App\Models\AnnualDeclaration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AnnualDeclarationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            return;
        }

        // Criar declarações dos últimos 2 anos
        $years = [
            Carbon::now()->year - 1, // Ano passado
            Carbon::now()->year - 2, // Ano retrasado
        ];

        foreach ($years as $year) {
            // Valores simulados
            $totalRevenue = fake()->randomFloat(2, 80000, 95000);
            $totalDasPaid = 71.60 * 12; // DAS mensal * 12 meses
            $totalInvoices = 12;

            AnnualDeclaration::create([
                'user_id' => $user->id,
                'reference_year' => $year,
                'total_revenue' => $totalRevenue,
                'total_das_paid' => $totalDasPaid,
                'total_invoices' => $totalInvoices,
                'generated_at' => Carbon::create($year + 1, 5, 15), // Maio do ano seguinte
            ]);
        }
    }
}
