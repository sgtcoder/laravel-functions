<?php

namespace SgtCoder\LaravelFunctions\Middleware;

use Closure;

class HttpsProtocol
{

    public function handle($request, Closure $next)
    {
        if (!env('DISABLE_SSL', FALSE)) {
            if (!$request->secure()) {
                return redirect()->secure($request->getRequestUri());
            }
        }

        return $next($request);
    }
}
