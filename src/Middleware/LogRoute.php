<?php

namespace SgtCoder\LaravelFunctions\Middleware;

use App\Models\LogRoute as LogRouteModel;
use Closure;
use Illuminate\Http\Request;

class LogRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $filtered_request_body = $request->all();

        // @phpstan-ignore-next-line
        $ignorable = (new \App\Models\LogRoute)->ignorable;

        foreach ($ignorable ?? [] as $ignore) {
            if (isset($filtered_request_body[$ignore])) $filtered_request_body[$ignore] = 'NULLED';
        }

        $user = request()->user();

        // @phpstan-ignore-next-line
        $log = LogRouteModel::create([
            'model_type' => ($user) ? get_class($user) : null,
            'model_id' => $user->id ?? null,
            'api_provider' => 'system',
            'uri' => $request->getUri(),
            'request_headers' => $request->header(),
            'request_body' => $filtered_request_body,
            'method' => $request->getMethod(),
            'ip' => $request->ip(),
            'http_code' => $response->getStatusCode(),
        ]);

        return $response;
    }
}
