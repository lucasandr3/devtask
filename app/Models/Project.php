<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'company_id',
        'client_id',
        'created_by',
        'name',
        'description',
        'status',
        'starts_at',
        'ends_at',
        'budget',
        'hourly_rate',
    ];

    protected function casts(): array
    {
        return [
            'status' => ProjectStatus::class,
            'starts_at' => 'date',
            'ends_at' => 'date',
            'budget' => 'decimal:2',
            'hourly_rate' => 'decimal:2',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $companyId = auth()->user()?->current_company_id;

        if (!$companyId) {
            abort(404);
        }

        $query = $this->where($field ?? $this->getRouteKeyName(), $value)
            ->where('company_id', $companyId);

        if (\App\Support\CurrentCompany::isMember()) {
            $query->whereHas('tasks', fn ($q) => $q->where('assigned_to', auth()->id()));
        }

        return $query->firstOrFail();
    }
}
