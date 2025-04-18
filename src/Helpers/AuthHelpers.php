<?php

if (!function_exists('get_auth_prefix')) {
    /**
     * get_auth_prefix
     *
     * @return mixed
     */
    function get_auth_prefix()
    {
        $route = str()->of(request()->route()->getName())->explode('.');

        return $route[0] ?? null;
    }
}

if (!function_exists('get_intended_route')) {
    /**
     * get_intended_route
     *
     * @param  mixed $prefix
     * @return mixed
     */
    function get_intended_route($prefix)
    {
        $intended_url = redirect()->intended()->getTargetUrl();

        $route = \Route::getRoutes()->match(\Request::create($intended_url))->getName();

        $route = str()->of($route)->explode('.');

        if (($route[0] ?? null) == $prefix) {
            return $intended_url;
        }

        return null;
    }
}

if (!function_exists('get_guards')) {
    /**
     * get_guards
     *
     * @return mixed
     */
    function get_guards()
    {
        // @phpstan-ignore-next-line
        $guards = collect(\App\Models\Permission::defaultPermissions())->keys()->mapWithKeys(function ($guard) {
            return [$guard => ucwords($guard)];
        });

        return $guards;
    }
}

if (!function_exists('get_guard_permissions')) {
    /**
     * get_guard_permissions
     *
     * @param  mixed $guard_name
     * @param  mixed $name_only
     * @return mixed
     */
    function get_guard_permissions($guard_name, $name_only = false)
    {
        // @phpstan-ignore-next-line
        $permissions = \App\Models\Permission::where('guard_name', $guard_name)->get();

        if ($name_only) return $permissions->pluck('name')->toArray();

        return $permissions;
    }
}

if (!function_exists('get_guard_data')) {
    /**
     * get_guard_data
     *
     * @param string $url
     * @return array
     */
    function get_guard_data($url)
    {
        $prefix = \Route::getRoutes()->match(\Request::create($url))->getPrefix();
        $prefix = ltrim($prefix, '/');

        $middlewares = \Route::getRoutes()->match(\Request::create($url))->gatherMiddleware();
        $middlewares = collect($middlewares)->filter(function ($middleware) {
            return str()->startsWith($middleware, 'auth');
        })->first();

        $guard = str()->of($middlewares)->replace('auth:', '')->toString();

        if ($guard == 'auth') {
            $guard = 'web';
        }

        return [
            'prefix' => $prefix,
            'guard' => $guard,
        ];
    }
}

if (!function_exists('require_token')) {
    /**
     * require_token
     *
     * @param  mixed $tokens
     * @return mixed
     */
    function require_token($tokens)
    {
        $tokens = is_array($tokens) ? $tokens : explode(',', $tokens);
        $request_token = request()->header('token') ?? request('token');
        if (!in_array($request_token, $tokens)) {
            abort(403, 'Unauthorized');
        }
    }
}
