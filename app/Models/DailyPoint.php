<?php

namespace App\Models;

use App\Enums\DailyPointStatus;
use App\Enums\PunchType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class DailyPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_date',
        'entry_time',
        'lunch_out_time',
        'lunch_return_time',
        'exit_time',
        'extra_start_time',
        'extra_end_time',
        'normal_minutes',
        'extra_minutes',
        'total_minutes',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'work_date' => 'date',
            'entry_time' => 'datetime',
            'lunch_out_time' => 'datetime',
            'lunch_return_time' => 'datetime',
            'exit_time' => 'datetime',
            'extra_start_time' => 'datetime',
            'extra_end_time' => 'datetime',
            'status' => DailyPointStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function canPunch(PunchType $type): bool
    {
        $order = $type->order();
        
        // Verifica se todos os tipos anteriores foram preenchidos
        foreach (PunchType::cases() as $punchType) {
            if ($punchType->order() < $order) {
                $field = $punchType->fieldName();
                if (!$this->$field) {
                    return false;
                }
            }
        }

        // Hora extra só pode ser registrada após saída normal
        if (in_array($type, [PunchType::EXTRA_START, PunchType::EXTRA_END])) {
            return (bool) $this->exit_time;
        }

        return true;
    }

    public function getNextPunchType(): ?PunchType
    {
        // Primeiro verifica as batidas obrigatórias (sem horas extras)
        $requiredTypes = [
            PunchType::ENTRY,
            PunchType::LUNCH_OUT,
            PunchType::LUNCH_RETURN,
            PunchType::EXIT,
        ];

        foreach ($requiredTypes as $type) {
            $field = $type->fieldName();
            if (!$this->$field) {
                return $type;
            }
        }

        // Se todas as batidas obrigatórias foram preenchidas, verifica horas extras (opcionais)
        // Mas só mostra se a saída já foi registrada
        if ($this->exit_time) {
            if (!$this->extra_start_time) {
                return PunchType::EXTRA_START;
            }
            if ($this->extra_start_time && !$this->extra_end_time) {
                return PunchType::EXTRA_END;
            }
        }

        return null;
    }

    public function calculateMinutes(): void
    {
        $normalMinutes = 0;
        $extraMinutes = 0;

        // Calcula horas normais: (saída almoço - entrada) + (saída - volta almoço)
        if ($this->entry_time && $this->lunch_out_time && $this->lunch_return_time && $this->exit_time) {
            // entry_time é datetime, então podemos usar diretamente
            $entry = $this->entry_time instanceof Carbon ? $this->entry_time->copy() : Carbon::parse($this->entry_time);
            $lunchOut = $this->lunch_out_time instanceof Carbon ? $this->lunch_out_time->copy() : Carbon::parse($this->lunch_out_time);
            $lunchReturn = $this->lunch_return_time instanceof Carbon ? $this->lunch_return_time->copy() : Carbon::parse($this->lunch_return_time);
            $exit = $this->exit_time instanceof Carbon ? $this->exit_time->copy() : Carbon::parse($this->exit_time);
            
            // Calcula diferença em minutos (absolute = false para manter sinal, mas usamos a ordem correta)
            $morning = $entry->diffInMinutes($lunchOut, false);
            $afternoon = $lunchReturn->diffInMinutes($exit, false);
            
            // Garantir que os valores sejam positivos
            $normalMinutes = abs($morning) + abs($afternoon);
        }

        // Calcula horas extras: (fim extra - início extra)
        if ($this->extra_start_time && $this->extra_end_time) {
            $extraStart = $this->extra_start_time instanceof Carbon ? $this->extra_start_time->copy() : Carbon::parse($this->extra_start_time);
            $extraEnd = $this->extra_end_time instanceof Carbon ? $this->extra_end_time->copy() : Carbon::parse($this->extra_end_time);
            
            $extraMinutes = abs($extraStart->diffInMinutes($extraEnd, false));
        }

        $this->normal_minutes = $normalMinutes;
        $this->extra_minutes = $extraMinutes;
        $this->total_minutes = $normalMinutes + $extraMinutes;
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
}
