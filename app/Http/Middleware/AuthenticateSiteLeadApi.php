<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateSiteLeadApi
{
    public function handle(Request $request, Closure $next): Response
    {
        $configuredToken = config('site-lead.api_token');
        $companyId = config('site-lead.company_id');

        if (! $configuredToken || ! $companyId) {
            return response()->json([
                'message' => 'API de contatos do site não configurada.',
            ], 503);
        }

        $token = $this->extractToken($request);

        if (! $token || ! hash_equals($configuredToken, $token)) {
            return response()->json([
                'message' => 'Token inválido.',
            ], 401);
        }

        $request->attributes->set('site_lead_company_id', (int) $companyId);

        return $next($request);
    }

    private function extractToken(Request $request): ?string
    {
        $header = $request->header('Authorization');

        if ($header && str_starts_with($header, 'Bearer ')) {
            return substr($header, 7);
        }

        return $request->header('X-Site-Lead-Token');
    }
}
