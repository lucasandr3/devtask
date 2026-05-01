<?php

namespace App\Models;

use App\Enums\MonthlyReportStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonthlyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference_month',
        'contract_minutes',
        'normal_minutes',
        'extra_minutes',
        'total_minutes',
        'balance_minutes',
        'status',
        'approver_name',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'reference_month' => 'date',
            'status' => MonthlyReportStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dailyPoints(): HasMany
    {
        $startOfMonth = $this->reference_month->copy()->startOfMonth();
        $endOfMonth = $this->reference_month->copy()->endOfMonth();

        return $this->hasMany(DailyPoint::class, 'user_id', 'user_id')
            ->whereBetween('work_date', [$startOfMonth, $endOfMonth]);
    }

    public function getContractHoursFormattedAttribute(): string
    {
        return minutesToHours($this->contract_minutes);
    }

    public function getNormalHoursFormattedAttribute(): string
    {
        return minutesToHours($this->normal_minutes);
    }

    public function getExtraHoursFormattedAttribute(): string
    {
        return minutesToHours($this->extra_minutes);
    }

    public function getTotalHoursFormattedAttribute(): string
    {
        return minutesToHours($this->total_minutes);
    }

    public function getBalanceHoursFormattedAttribute(): string
    {
        return minutesToHours($this->balance_minutes);
    }
}
