<?php

namespace App\Models;

use App\Enums\DasPaymentStatus;
use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DasPayment extends Model
{
    use BelongsToCompany, HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'reference_month',
        'due_date',
        'payment_date',
        'amount',
        'status',
        'receipt_file',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'reference_month' => 'date',
            'due_date' => 'date',
            'payment_date' => 'date',
            'amount' => 'decimal:2',
            'status' => DasPaymentStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'R$ ' . number_format($this->amount, 2, ',', '.');
    }

    public function getReferenceMonthFormattedAttribute(): string
    {
        return $this->reference_month->format('m/Y');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($dasPayment) {
            // Atualiza status automaticamente baseado em payment_date e due_date
            if ($dasPayment->payment_date) {
                $dasPayment->status = DasPaymentStatus::PAID;
            } elseif ($dasPayment->due_date && $dasPayment->due_date->isPast() && !$dasPayment->payment_date) {
                $dasPayment->status = DasPaymentStatus::OVERDUE;
            } elseif (!$dasPayment->exists || $dasPayment->isDirty(['payment_date', 'due_date'])) {
                $dasPayment->status = DasPaymentStatus::PENDING;
            }
        });
    }
}
