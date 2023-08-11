<?php

namespace SgtCoder\LaravelFunctions\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $config_name)
    {
        $response = $next($request);

        if ($config_name) {
            $valid_token = config($config_name);
            $token = $request->token;

            if ($token == $valid_token) {
                return $response;
            }
        }

        Log::channel('api_log')->error('WEBAPPS INVALID', ['header' => request()->header(), 'request' => request()->all()]);

        return response()->json(['status' => false, 'message' => 'Access denied'], 403);
    }
}
