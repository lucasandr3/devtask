<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        require_once app_path('Helpers/TimeHelper.php');
        require_once app_path('Helpers/StatusHelper.php');

        \Carbon\Carbon::setLocale('pt_BR');

        RateLimiter::for('site-leads', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });
    }
}
