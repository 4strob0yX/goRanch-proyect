<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // <--- AGREGA ESTO

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191); // <--- AGREGA ESTO

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}