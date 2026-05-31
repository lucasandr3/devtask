<?php

namespace App\Models;

use App\Enums\FinancialTransactionStatus;
use App\Enums\FinancialTransactionType;
use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialTransaction extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id',
        'user_id',
        'client_id',
        'project_id',
        'type',
        'status',
        'description',
        'amount',
        'due_date',
        'paid_at',
        'category',
        'notes',
        'installment_group_id',
        'installment_number',
        'installment_count',
    ];

    protected function casts(): array
    {
        return [
            'type' => FinancialTransactionType::class,
            'status' => FinancialTransactionStatus::class,
            'amount' => 'decimal:2',
            'due_date' => 'date',
            'paid_at' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'R$ '.number_format((float) $this->amount, 2, ',', '.');
    }

    public function isInstallment(): bool
    {
        return $this->installment_group_id !== null
            && $this->installment_count !== null
            && $this->installment_count > 1;
    }

    public function getInstallmentLabelAttribute(): ?string
    {
        if (! $this->isInstallment()) {
            return null;
        }

        return "{$this->installment_number}/{$this->installment_count}";
    }

    public function scopeInstallmentGroups($query)
    {
        return $query->whereNotNull('installment_group_id');
    }
}
