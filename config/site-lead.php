<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Token
    |--------------------------------------------------------------------------
    |
    | Token enviado pelo site (header Authorization: Bearer ou X-Site-Lead-Token).
    | Gere um valor longo e aleatório: php -r "echo bin2hex(random_bytes(32));"
    |
    */

    'api_token' => env('SITE_LEAD_API_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Company ID
    |--------------------------------------------------------------------------
    |
    | Empresa que receberá os contatos enviados pela API pública.
    |
    */

    'company_id' => env('SITE_LEAD_COMPANY_ID'),

    /*
    |--------------------------------------------------------------------------
    | CORS (navegador no domínio do site)
    |--------------------------------------------------------------------------
    |
    | Origens permitidas para POST direto do front (ex.: https://ziontech.com.br).
    | Separe várias origens por vírgula. Deixe vazio para permitir qualquer origem
    | (não recomendado em produção).
    |
    */

    'cors_origins' => array_values(array_filter(array_map(
        trim(...),
        explode(',', (string) env('SITE_LEAD_CORS_ORIGINS', ''))
    ))),

];
