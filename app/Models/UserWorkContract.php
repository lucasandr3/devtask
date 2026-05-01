<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class UserWorkContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'contract_value',
        'monthly_minutes',
        'start_date',
        'end_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'contract_value' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActiveOnDate(Carbon $date): bool
    {
        if ($this->start_date->gt($date)) {
            return false;
        }

        if ($this->end_date && $this->end_date->lt($date)) {
            return false;
        }

        return true;
    }

    public function scopeActive($query, Carbon $date)
    {
        return $query->where('start_date', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $date);
            });
    }

    /**
     * Resolve o modelo para route model binding, garantindo que apenas contratos
     * do usuário autenticado sejam encontrados.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $userId = auth()->check() ? auth()->id() : null;
        
        if (!$userId) {
            abort(404);
        }

        return $this->where($field ?? $this->getRouteKeyName(), $value)
            ->where('user_id', $userId)
            ->firstOrFail();
    }
}
