<?php

namespace SgtCoder\LaravelFunctions;

use Illuminate\Support\ServiceProvider;

// https://laravel.com/docs/10.x/packages
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
    }
}
