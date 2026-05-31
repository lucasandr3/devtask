<?php

namespace App\Models;

use App\Enums\InvoicePaymentStatus;
use App\Enums\InvoiceType;
use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use BelongsToCompany, HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'client_id',
        'project_id',
        'numero',
        'serie',
        'data_emissao',
        'valor',
        'descricao',
        'arquivo',
        'service_code',
        'iss_value',
        'tax_amount',
        'invoice_type',
        'payment_status',
    ];

    protected function casts(): array
    {
        return [
            'data_emissao' => 'date',
            'valor' => 'decimal:2',
            'iss_value' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'invoice_type' => InvoiceType::class,
            'payment_status' => InvoicePaymentStatus::class,
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

    public function getFormattedValorAttribute(): string
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
    }

    public function getFormattedIssValueAttribute(): string
    {
        return $this->iss_value ? 'R$ ' . number_format($this->iss_value, 2, ',', '.') : '-';
    }

    public function getFormattedTaxAmountAttribute(): string
    {
        return $this->tax_amount ? 'R$ ' . number_format($this->tax_amount, 2, ',', '.') : '-';
    }
}
