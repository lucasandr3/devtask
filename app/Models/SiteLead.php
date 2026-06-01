<?php

namespace App\Models;

use App\Enums\SiteLeadSegment;
use App\Enums\SiteLeadStatus;
use App\Models\Concerns\BelongsToCompany;
use App\Support\CurrentCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteLead extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id',
        'client_id',
        'name',
        'email',
        'company_name',
        'phone',
        'segment',
        'message',
        'source',
        'privacy_consent',
        'privacy_policy_version',
        'privacy_consented_at',
        'status',
        'read_at',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'status' => SiteLeadStatus::class,
            'read_at' => 'datetime',
            'privacy_consent' => 'boolean',
            'privacy_consented_at' => 'datetime',
        ];
    }

    public function getSegmentLabelAttribute(): string
    {
        return SiteLeadSegment::labelFor($this->segment);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function isConverted(): bool
    {
        return $this->client_id !== null;
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
