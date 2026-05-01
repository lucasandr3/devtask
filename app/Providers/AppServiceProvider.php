<?php

namespace App\Providers;

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
        // Registrar helpers
        require_once app_path('Helpers/TimeHelper.php');
        require_once app_path('Helpers/StatusHelper.php');
    }
}
