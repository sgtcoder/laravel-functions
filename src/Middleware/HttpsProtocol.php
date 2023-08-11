<?php

namespace SgtCoder\LaravelFunctions\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

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
