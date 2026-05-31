<?php

namespace App\Services;

use App\Enums\FinancialTransactionStatus;
use App\Enums\FinancialTransactionType;
use App\Enums\InstallmentInterval;
use App\Models\FinancialTransaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FinancialInstallmentService
{
    /**
     * @return Collection<int, FinancialTransaction>
     */
    public function createInstallments(array $data): Collection
    {
        $count = (int) $data['installment_count'];
        $total = round((float) $data['amount'], 2);
        $groupId = (string) Str::uuid();
        $firstDue = Carbon::parse($data['due_date']);
        $interval = InstallmentInterval::from($data['installment_interval'] ?? InstallmentInterval::MONTHLY->value);
        $baseDescription = $data['description'];

        $amounts = $this->splitAmount($total, $count);
        $created = collect();

        foreach ($amounts as $index => $amount) {
            $number = $index + 1;
            $dueDate = $index === 0
                ? $firstDue->copy()
                : $interval->addTo($firstDue, $index);

            $created->push(FinancialTransaction::create([
                'company_id' => $data['company_id'],
                'user_id' => $data['user_id'],
                'client_id' => $data['client_id'] ?? null,
                'project_id' => $data['project_id'] ?? null,
                'type' => $data['type'],
                'status' => FinancialTransactionStatus::PENDING,
                'description' => $this->installmentDescription($baseDescription, $number, $count),
                'amount' => $amount,
                'due_date' => $dueDate,
                'paid_at' => null,
                'category' => $data['category'] ?? null,
                'notes' => $data['notes'] ?? null,
                'installment_group_id' => $groupId,
                'installment_number' => $number,
                'installment_count' => $count,
            ]));
        }

        return $created;
    }

    /**
     * @return array<int, float>
     */
    public function splitAmount(float $total, int $count): array
    {
        if ($count < 1) {
            return [];
        }

        if ($count === 1) {
            return [round($total, 2)];
        }

        $base = floor(($total / $count) * 100) / 100;
        $amounts = array_fill(0, $count - 1, $base);
        $sum = round($base * ($count - 1), 2);
        $amounts[] = round($total - $sum, 2);

        return $amounts;
    }

    public function installmentDescription(string $base, int $number, int $total): string
    {
        return sprintf('%s (%d/%d)', $base, $number, $total);
    }

    public function deleteGroup(string $groupId, int $companyId): int
    {
        return FinancialTransaction::where('company_id', $companyId)
            ->where('installment_group_id', $groupId)
            ->delete();
    }

    /**
     * @return Collection<int, FinancialTransaction>
     */
    public function siblings(FinancialTransaction $transaction): Collection
    {
        if (! $transaction->installment_group_id) {
            return collect([$transaction]);
        }

        return FinancialTransaction::where('company_id', $transaction->company_id)
            ->where('installment_group_id', $transaction->installment_group_id)
            ->orderBy('installment_number')
            ->get();
    }
}
