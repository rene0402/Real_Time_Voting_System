<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        // Force HTTPS in production so all generated URLs use https://
        // and browsers do not show mixed-content warnings.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
