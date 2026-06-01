<?php

namespace App\Models;

use App\Enums\SiteLeadStatus;
use App\Models\Concerns\BelongsToCompany;
use App\Support\CurrentCompany;
use Illuminate\Database\Eloquent\Model;

class SiteLead extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'company_name',
        'phone',
        'segment',
        'message',
        'status',
        'read_at',
        'ip',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'status' => SiteLeadStatus::class,
            'read_at' => 'datetime',
        ];
    }

    public function markAsRead(): void
    {
        if ($this->status === SiteLeadStatus::NEW) {
            $this->update([
                'status' => SiteLeadStatus::READ,
                'read_at' => now(),
            ]);
        }
    }

    public function resolveRouteBinding($value, $field = null)
    {
        abort_unless(CurrentCompany::canManageTeam(), 403);

        return $this->where($field ?? $this->getRouteKeyName(), $value)
            ->where('company_id', CurrentCompany::id())
            ->firstOrFail();
    }
}
