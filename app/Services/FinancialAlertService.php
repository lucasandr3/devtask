<?php

namespace App\Services;

use App\Enums\DasPaymentStatus;
use App\Enums\FinancialTransactionStatus;
use App\Enums\FinancialTransactionType;
use App\Enums\InvoicePaymentStatus;
use App\Models\DasPayment;
use App\Models\FinancialTransaction;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class FinancialAlertService
{
    public const DUE_SOON_DAYS = 7;

    public function collect(int $companyId): Collection
    {
        $today = Carbon::today();
        $soon = $today->copy()->addDays(self::DUE_SOON_DAYS);
        $alerts = collect();

        DasPayment::where('company_id', $companyId)
            ->whereIn('status', [DasPaymentStatus::PENDING, DasPaymentStatus::OVERDUE])
            ->orderBy('due_date')
            ->get()
            ->each(function (DasPayment $das) use ($alerts, $today, $soon) {
                $isOverdue = $das->status === DasPaymentStatus::OVERDUE
                    || ($das->due_date && $das->due_date->lt($today));

                if ($isOverdue) {
                    $alerts->push($this->makeAlert(
                        severity: 'danger',
                        type: 'finance_das_overdue',
                        title: 'Guia tributária vencida',
                        message: 'Ref. '.$das->reference_month->format('m/Y').' — venceu em '.$das->due_date->format('d/m/Y'),
                        amount: (float) $das->amount,
                        dueDate: $das->due_date,
                        url: route('das.edit', $das),
                    ));
                } elseif ($das->due_date && $das->due_date->lte($soon)) {
                    $alerts->push($this->makeAlert(
                        severity: 'warning',
                        type: 'finance_das_due_soon',
                        title: 'Guia tributária a vencer',
                        message: 'Vence em '.$das->due_date->locale('pt_BR')->diffForHumans($today, true),
                        amount: (float) $das->amount,
                        dueDate: $das->due_date,
                        url: route('das.edit', $das),
                    ));
                }
            });

        FinancialTransaction::where('company_id', $companyId)
            ->where('status', FinancialTransactionStatus::PENDING)
            ->orderBy('due_date')
            ->get()
            ->each(function (FinancialTransaction $tx) use ($alerts, $today, $soon) {
                $isReceivable = $tx->type === FinancialTransactionType::RECEIVABLE;
                $isOverdue = $tx->due_date->lt($today);

                if ($isOverdue) {
                    $alerts->push($this->makeAlert(
                        severity: 'danger',
                        type: $isReceivable ? 'finance_receivable_overdue' : 'finance_payable_overdue',
                        title: $isReceivable ? 'Recebível em atraso' : 'Conta a pagar vencida',
                        message: $tx->description.' — venceu em '.$tx->due_date->format('d/m/Y'),
                        amount: (float) $tx->amount,
                        dueDate: $tx->due_date,
                        url: route('financeiro.lancamentos.edit', $tx),
                    ));
                } elseif ($tx->due_date->lte($soon)) {
                    $alerts->push($this->makeAlert(
                        severity: 'warning',
                        type: $isReceivable ? 'finance_receivable_due_soon' : 'finance_payable_due_soon',
                        title: $isReceivable ? 'Recebível a vencer' : 'Conta a pagar próxima',
                        message: $tx->description.' — vence em '.$tx->due_date->locale('pt_BR')->diffForHumans($today, true),
                        amount: (float) $tx->amount,
                        dueDate: $tx->due_date,
                        url: route('financeiro.lancamentos.edit', $tx),
                    ));
                }
            });

        Invoice::where('company_id', $companyId)
            ->whereIn('payment_status', [InvoicePaymentStatus::PENDING, InvoicePaymentStatus::OVERDUE])
            ->orderByDesc('data_emissao')
            ->get()
            ->each(function (Invoice $invoice) use ($alerts) {
                $isOverdue = $invoice->payment_status === InvoicePaymentStatus::OVERDUE;

                $alerts->push($this->makeAlert(
                    severity: $isOverdue ? 'danger' : 'warning',
                    type: $isOverdue ? 'finance_invoice_overdue' : 'finance_invoice_pending',
                    title: $isOverdue ? 'Nota fiscal em atraso' : 'Nota fiscal não recebida',
                    message: 'NF '.$invoice->numero.($invoice->descricao ? ' — '.$invoice->descricao : ''),
                    amount: (float) $invoice->valor,
                    dueDate: $invoice->data_emissao,
                    url: route('notas-fiscais.edit', $invoice),
                ));
            });

        return $alerts
            ->sortBy([
                fn (array $a) => match ($a['severity']) {
                    'danger' => 0,
                    'warning' => 1,
                    default => 2,
                },
                fn (array $a) => $a['due_date']?->timestamp ?? 0,
            ])
            ->values();
    }

    private function makeAlert(
        string $severity,
        string $type,
        string $title,
        string $message,
        float $amount,
        ?Carbon $dueDate,
        string $url,
    ): array {
        return [
            'severity' => $severity,
            'type' => $type,
            'icon' => 'finance',
            'title' => $title,
            'message' => $message,
            'amount' => $amount,
            'formatted_amount' => 'R$ '.number_format($amount, 2, ',', '.'),
            'due_date' => $dueDate,
            'due_date_label' => $dueDate?->format('d/m/Y'),
            'url' => $url,
            'at' => $dueDate ?? now(),
        ];
    }
}
