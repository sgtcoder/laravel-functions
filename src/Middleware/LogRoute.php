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
        /**
         * LARAVEL_START is defined in public/index.php before the framework boots, so the
         * recorded total covers bootstrapping and the whole middleware stack rather than
         * only the portion of the request that runs inside this middleware. It is not
         * defined for requests that do not enter through public/index.php.
         */
        $started_at = defined('LARAVEL_START')
            ? LARAVEL_START
            : ($request->server('REQUEST_TIME_FLOAT') ?: microtime(true));

        $response = $next($request);

        $total_ms = (int) round((microtime(true) - $started_at) * 1000);

        $filtered_request_body = $request->all();

        // @phpstan-ignore-next-line
        $ignorable = (new \App\Models\LogRoute)->ignorable;

        foreach ($ignorable ?? [] as $ignore) {
            if (isset($filtered_request_body[$ignore])) $filtered_request_body[$ignore] = 'NULLED';
        }

        $user = request()->user();

        // @phpstan-ignore-next-line
        $log = LogRouteModel::create([
            'model_type' => $user ? $user::class : null,
            'model_id' => $user->id ?? null,
            'api_provider' => 'system',
            'uri' => $request->getUri(),
            'request_headers' => $request->header(),
            'request_body' => $filtered_request_body,
            'response_headers' => $response->headers->all(),
            'response_body' => $this->getResponseBody($response),
            'method' => $request->getMethod(),
            'ip' => $request->ip(),
            'http_code' => $response->getStatusCode(),
            'total_ms' => $total_ms,
        ]);

        return $response;
    }

    /**
     * Get the response body content safely
     *
     * @param \Illuminate\Http\Response $response
     * @return mixed
     */
    private function getResponseBody($response)
    {
        try {
            $content = $response->getContent();

            // Try to decode JSON responses
            if (
                $response->headers->get('Content-Type') &&
                str_contains($response->headers->get('Content-Type'), 'application/json')
            ) {
                return json_decode($content, true);
            }

            // For other content types, return as string (truncated if too long)
            return strlen($content) > 10000 ? substr($content, 0, 10000) . '...' : $content;
        } catch (\Exception $e) {
            return 'Error retrieving response body: ' . $e->getMessage();
        }
    }
}
