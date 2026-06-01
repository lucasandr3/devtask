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

];
