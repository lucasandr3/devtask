<?php

namespace App\Http\Controllers\Api;

use App\Enums\SiteLeadStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSiteLeadRequest;
use App\Models\SiteLead;
use Illuminate\Http\JsonResponse;

class SiteLeadController extends Controller
{
    public function store(StoreSiteLeadRequest $request): JsonResponse
    {
        $lead = SiteLead::create([
            ...$request->leadAttributes(),
            'company_id' => $request->attributes->get('site_lead_company_id'),
            'status' => SiteLeadStatus::NEW,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Contato recebido com sucesso.',
            'id' => $lead->id,
        ], 201);
    }
}
