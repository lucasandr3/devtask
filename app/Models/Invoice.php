<?php

namespace App\Models;

use App\Enums\InvoiceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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
    ];

    protected function casts(): array
    {
        return [
            'data_emissao' => 'date',
            'valor' => 'decimal:2',
            'iss_value' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'invoice_type' => InvoiceType::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
