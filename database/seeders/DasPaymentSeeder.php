<?php

namespace Database\Seeders;

use App\Enums\DasPaymentStatus;
use App\Models\DasPayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DasPaymentSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            return;
        }

        // Criar pagamentos DAS dos últimos 12 meses
        for ($i = 12; $i >= 0; $i--) {
            $referenceMonth = Carbon::now()->subMonths($i)->startOfMonth();
            $dueDate = $referenceMonth->copy()->addMonth()->day(20);
            
            // Valor do DAS MEI (valor fixo atual)
            $amount = 71.60;

            // Determina status e data de pagamento
            $isPaid = $i > 0; // Meses anteriores estão pagos
            $paymentDate = $isPaid 
                ? $dueDate->copy()->subDays(fake()->numberBetween(1, 10)) 
                : null;

            DasPayment::create([
                'user_id' => $user->id,
                'reference_month' => $referenceMonth,
                'due_date' => $dueDate,
                'payment_date' => $paymentDate,
                'amount' => $amount,
                'status' => $isPaid ? DasPaymentStatus::PAID : DasPaymentStatus::PENDING,
                'notes' => fake()->boolean(20) ? 'Pago via PIX' : null,
            ]);
        }
    }
}
