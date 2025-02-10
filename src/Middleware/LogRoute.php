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
        $request_array = request()->all();

        // @phpstan-ignore-next-line
        $ignorable = (new \App\Models\LogRoute)->ignorable;

        foreach ($ignorable as $ignore) {
            if (isset($request_array[$ignore])) $request_array[$ignore] = 'NULLED';
        }

        $user = request()->user() ?? auth('sanctum')->user();

        // @phpstan-ignore-next-line
        $log = LogRouteModel::create([
            'model_type' => ($user) ? get_class($user) : null,
            'model_id' => $user->id ?? null,
            'uri' => request()->getUri(),
            'request_body' => json_encode($request_array),
            'method' => request()->getMethod(),
            'ip' => request()->ip(),
            'http_code' => $response->getStatusCode(),
        ]);

        return $response;
    }
}
