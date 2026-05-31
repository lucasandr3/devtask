<?php

namespace App\Models\Concerns;

use App\Models\Company;
use App\Support\CurrentCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToCompany
{
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeForCurrentCompany($query)
    {
        return $query->where('company_id', CurrentCompany::id());
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if (! CurrentCompany::canViewFinance()) {
            abort(403);
        }

        return $this->where($field ?? $this->getRouteKeyName(), $value)
            ->where('company_id', CurrentCompany::id())
            ->firstOrFail();
    }
}
