<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::aliasMiddleware('role', \App\Http\Middleware\CheckRoleAccess::class);
    }
}

