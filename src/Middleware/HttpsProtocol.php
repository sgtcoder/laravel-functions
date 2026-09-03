<?php

namespace SgtCoder\LaravelFunctions\Middleware;

use Closure;

class HttpsProtocol
{

    public function handle($request, Closure $next)
    {
        // config() rather than env(): env() returns null once config:cache has run.
        if (!config('laravel-functions.disable_ssl', false)) {
            if (!$request->secure()) {
                return redirect()->secure($request->getRequestUri());
            }
        }

        return $next($request);
    }
}
