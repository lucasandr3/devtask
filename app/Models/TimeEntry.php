<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'started_at',
        'ended_at',
        'duration_minutes',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'duration_minutes' => 'integer',
        ];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isRunning(): bool
    {
        return $this->ended_at === null;
    }

    public function stop(): void
    {
        $endedAt = Carbon::now();
        $startedAt = $this->started_at instanceof Carbon
            ? $this->started_at
            : Carbon::parse($this->started_at);

        $durationMinutes = max(1, (int) ceil($startedAt->diffInSeconds($endedAt) / 60));

        $this->update([
            'ended_at' => $endedAt,
            'duration_minutes' => $durationMinutes,
        ]);
    }
}
