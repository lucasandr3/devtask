<?php

/**
 * Espelho de src/app/shared/constants/site-legal.ts (site Zion Tech).
 * Ao alterar a política no Angular, atualize SITE_PRIVACY_POLICY_VERSION no .env.
 */

$privacyPolicyVersion = env('SITE_PRIVACY_POLICY_VERSION', '2026-06-02');

$extraVersions = array_values(array_filter(array_map(
    trim(...),
    explode(',', (string) env('SITE_ACCEPTED_PRIVACY_VERSIONS', ''))
)));

$acceptedPrivacyVersions = array_values(array_unique(array_merge(
    [$privacyPolicyVersion],
    $extraVersions !== [] ? $extraVersions : ['2026-06-01', '2026-06-02'],
)));

return [

    'privacy_policy_version' => $privacyPolicyVersion,

    'accepted_privacy_versions' => $acceptedPrivacyVersions,

    'legal' => [
        'controller_name' => 'Zion Tech',
        'city' => 'Uberlândia',
        'state' => 'MG',
        'country' => 'Brasil',
        'privacy_email' => env('SITE_PRIVACY_EMAIL', 'privacidade@ziontech.com.br'),
        'website_url' => env('SITE_WEBSITE_URL', 'https://ziontech.com.br'),
        'last_updated_label' => env('SITE_PRIVACY_LAST_UPDATED_LABEL', '1 de junho de 2026'),
    ],

    'cookie_consent_storage_key' => 'zion-cookie-consent-v1',
    'chat_privacy_consent_key' => 'zion-chat-privacy-consent-v1',

];
