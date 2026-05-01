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
        'status',
        'work_date',
        'user_id',
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

    public function pullRequests(): HasMany
    {
        return $this->hasMany(PullRequest::class);
    }

    /**
     * Resolve o modelo para route model binding, garantindo que apenas tarefas
     * do usuário autenticado sejam encontradas.
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
