<?php

$origins = config('site-lead.cors_origins', []);

return [

    'paths' => ['api/site-leads'],

    'allowed_methods' => ['POST', 'OPTIONS'],

    'allowed_origins' => $origins !== []
        ? $origins
        : (config('app.env') === 'local' ? ['*'] : []),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['Content-Type', 'Accept', 'Authorization', 'X-Site-Lead-Token'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
