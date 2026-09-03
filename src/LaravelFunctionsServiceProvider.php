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
            $this->publishes([
                __DIR__ . '/../config/laravel-functions.php' => config_path('laravel-functions.php'),
            ], 'laravel-functions-config');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * mergeConfigFrom is skipped once the application's config is cached, and
         * config:cache boots a fresh application to collect provider merges, so the env()
         * calls inside the config file are only ever evaluated at cache-build time.
         */
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-functions.php', 'laravel-functions');
    }
}
