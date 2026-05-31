<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'executor_notes',
        'internal_notes',
        'status',
        'work_date',
        'user_id',
        'project_id',
        'assigned_to',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'work_date' => 'date',
            'status' => TaskStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function pullRequests(): HasMany
    {
        return $this->hasMany(PullRequest::class);
    }

    public function totalTrackedMinutes(): int
    {
        return (int) $this->timeEntries()
            ->whereNotNull('ended_at')
            ->sum('duration_minutes');
    }

    public function runningTimeEntryFor(?int $userId = null): ?TimeEntry
    {
        $userId ??= auth()->id();

        return $this->timeEntries()
            ->where('user_id', $userId)
            ->whereNull('ended_at')
            ->first();
    }

    public function isAssignedToCurrentUser(): bool
    {
        return $this->assigned_to === auth()->id();
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $companyId = auth()->user()?->current_company_id;

        if (!$companyId) {
            abort(404);
        }

        return $this->where($field ?? $this->getRouteKeyName(), $value)
            ->whereHas('project', fn ($query) => $query->where('company_id', $companyId))
            ->when(
                \App\Support\CurrentCompany::isMember(),
                fn ($query) => $query->where('assigned_to', auth()->id())
            )
            ->firstOrFail();
    }
}
