<?php

use Illuminate\Support\Facades\URL;

if (! function_exists('back_url')) {
    /**
     * Resolve a safe "back" URL: ?return=, then Referer, then fallback.
     */
    function back_url(?string $fallback = null): string
    {
        $fallback = $fallback ?? route('painel');

        if ($return = request()->query('return')) {
            $decoded = is_string($return) ? urldecode($return) : null;
            if ($decoded && back_url_is_safe($decoded)) {
                return $decoded;
            }
        }

        if ($referer = request()->header('referer')) {
            if (back_url_is_safe($referer)) {
                return $referer;
            }
        }

        return $fallback;
    }
}

if (! function_exists('back_url_is_safe')) {
    function back_url_is_safe(string $url): bool
    {
        if (str_starts_with($url, '/')) {
            return ! str_starts_with($url, '//');
        }

        $parsed = parse_url($url);
        if (! $parsed || empty($parsed['host'])) {
            return false;
        }

        $allowedHosts = array_filter([
            parse_url((string) config('app.url'), PHP_URL_HOST),
            request()->getHost(),
        ]);

        return in_array($parsed['host'], $allowedHosts, true);
    }
}

if (! function_exists('route_with_return')) {
    /**
     * Append current URL as ?return= for dynamic back navigation.
     */
    function route_with_return(string $routeName, array $parameters = [], ?string $fromUrl = null): string
    {
        $fromUrl = $fromUrl ?? URL::full();
        $parameters['return'] = $fromUrl;

        return route($routeName, $parameters);
    }
}

if (! function_exists('route_preserve_return')) {
    /**
     * Keep ?return= when navigating between related screens.
     */
    function route_preserve_return(string $routeName, mixed $parameters = []): string
    {
        if (request()->filled('return')) {
            $parameters = is_array($parameters) ? $parameters : [$parameters];
            $parameters['return'] = request()->query('return');
        } else {
            return route_with_return($routeName, is_array($parameters) ? $parameters : [$parameters]);
        }

        return route($routeName, $parameters);
    }
}
