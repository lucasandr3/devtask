<?php

namespace Database\Seeders;

use App\Enums\InvoiceType;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            return;
        }

        $invoices = [
            [
                'numero' => '2025000001',
                'serie' => '1',
                'data_emissao' => Carbon::now()->subMonths(6)->startOfMonth()->addDays(15),
                'valor' => 8500.00,
                'descricao' => 'Serviços de desenvolvimento de software - Julho/2025',
                'service_code' => '01.05',
                'iss_value' => 170.00,
                'tax_amount' => 0,
                'invoice_type' => InvoiceType::SERVICE,
            ],
            [
                'numero' => '2025000002',
                'serie' => '1',
                'data_emissao' => Carbon::now()->subMonths(5)->startOfMonth()->addDays(15),
                'valor' => 8500.00,
                'descricao' => 'Serviços de desenvolvimento de software - Agosto/2025',
                'service_code' => '01.05',
                'iss_value' => 170.00,
                'tax_amount' => 0,
                'invoice_type' => InvoiceType::SERVICE,
            ],
            [
                'numero' => '2025000003',
                'serie' => '1',
                'data_emissao' => Carbon::now()->subMonths(4)->startOfMonth()->addDays(15),
                'valor' => 8500.00,
                'descricao' => 'Serviços de desenvolvimento de software - Setembro/2025',
                'service_code' => '01.05',
                'iss_value' => 170.00,
                'tax_amount' => 0,
                'invoice_type' => InvoiceType::SERVICE,
            ],
            [
                'numero' => '2025000004',
                'serie' => '1',
                'data_emissao' => Carbon::now()->subMonths(3)->startOfMonth()->addDays(15),
                'valor' => 9000.00,
                'descricao' => 'Serviços de desenvolvimento de software - Outubro/2025',
                'service_code' => '01.05',
                'iss_value' => 180.00,
                'tax_amount' => 0,
                'invoice_type' => InvoiceType::SERVICE,
            ],
            [
                'numero' => '2025000005',
                'serie' => '1',
                'data_emissao' => Carbon::now()->subMonths(2)->startOfMonth()->addDays(15),
                'valor' => 9000.00,
                'descricao' => 'Serviços de desenvolvimento de software - Novembro/2025',
                'service_code' => '01.05',
                'iss_value' => 180.00,
                'tax_amount' => 0,
                'invoice_type' => InvoiceType::SERVICE,
            ],
            [
                'numero' => '2025000006',
                'serie' => '1',
                'data_emissao' => Carbon::now()->subMonth()->startOfMonth()->addDays(15),
                'valor' => 9000.00,
                'descricao' => 'Serviços de desenvolvimento de software - Dezembro/2025',
                'service_code' => '01.05',
                'iss_value' => 180.00,
                'tax_amount' => 0,
                'invoice_type' => InvoiceType::SERVICE,
            ],
            [
                'numero' => '2026000001',
                'serie' => '1',
                'data_emissao' => Carbon::now()->startOfMonth()->addDays(15),
                'valor' => 9500.00,
                'descricao' => 'Serviços de desenvolvimento de software - Janeiro/2026',
                'service_code' => '01.05',
                'iss_value' => 190.00,
                'tax_amount' => 0,
                'invoice_type' => InvoiceType::SERVICE,
            ],
        ];

        foreach ($invoices as $invoiceData) {
            Invoice::create([
                'user_id' => $user->id,
                ...$invoiceData,
            ]);
        }
    }
}
