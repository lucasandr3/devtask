<?php

use App\Http\Controllers\Api\SiteLeadController;
use App\Http\Middleware\AuthenticateSiteLeadApi;
use Illuminate\Support\Facades\Route;

Route::middleware([
    AuthenticateSiteLeadApi::class,
    'throttle:site-leads',
])->group(function () {
    Route::post('/site-leads', [SiteLeadController::class, 'store']);
});
