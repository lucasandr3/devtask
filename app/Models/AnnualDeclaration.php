<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class AnnualDeclaration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference_year',
        'total_revenue',
        'total_das_paid',
        'total_invoices',
        'generated_at',
        'pdf_file',
    ];

    protected function casts(): array
    {
        return [
            'reference_year' => 'integer',
            'total_revenue' => 'decimal:2',
            'total_das_paid' => 'decimal:2',
            'generated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getInvoicesAttribute()
    {
        return Invoice::where('user_id', $this->user_id)
            ->whereYear('data_emissao', $this->reference_year)
            ->get();
    }

    public function getDasPaymentsAttribute()
    {
        return DasPayment::where('user_id', $this->user_id)
            ->whereYear('reference_month', $this->reference_year)
            ->where('status', 'paid')
            ->get();
    }

    public function getFormattedTotalRevenueAttribute(): string
    {
        return 'R$ ' . number_format($this->total_revenue, 2, ',', '.');
    }

    public function getFormattedTotalDasPaidAttribute(): string
    {
        return 'R$ ' . number_format($this->total_das_paid, 2, ',', '.');
    }

    public function getNetRevenueAttribute(): float
    {
        return $this->total_revenue - $this->total_das_paid;
    }

    public function getFormattedNetRevenueAttribute(): string
    {
        return 'R$ ' . number_format($this->net_revenue, 2, ',', '.');
    }
}
