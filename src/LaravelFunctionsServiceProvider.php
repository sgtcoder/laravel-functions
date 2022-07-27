<?php

namespace Titanium6\LaravelFunctions;

use Illuminate\Support\ServiceProvider;

// https://laravel.com/docs/8.x/packages
// https://darkghosthunter.medium.com/composer-using-your-own-local-package-2b252670d429
class LaravelFunctionsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {

        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        require_once __DIR__.'/Functions.php';
    }
}