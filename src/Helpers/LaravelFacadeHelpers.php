<?php

if (!function_exists('artisan')) {
    /**
     * artisan
     *
     * @return \Illuminate\Support\Facades\Artisan
     */
    function artisan()
    {
        return \Illuminate\Support\Facades\Artisan::getFacadeRoot();
    }
}

if (!function_exists('http')) {
    /**
     * http
     *
     * @return \Illuminate\Support\Facades\Http
     */
    function http()
    {
        return \Illuminate\Support\Facades\Http::getFacadeRoot();
    }
}

if (!function_exists('http_safe')) {
    /**
     * Same fluent API as Http::. get/post/put/patch/delete/head return an empty response on ConnectionException.
     * pool() returns the array of responses directly (not the wrapper).
     */
    function http_safe(): object
    {
        static $empty = null;
        $empty ??= new class {
            private static array $returns;

            public function __call(string $method, array $args): mixed
            {
                self::$returns ??= [
                    'status' => 0,
                    'getStatusCode' => 0,
                    'collect' => collect([]),
                    'json' => [],
                    'body' => '',
                    'successful' => false,
                    'effectiveUri' => null,
                    'headers' => [],
                    'transferStats' => fn() => new class {
                        public function getTransferTime(): float
                        {
                            return 0.0;
                        }
                    },
                ];
                $v = self::$returns[$method] ?? null;
                return $v instanceof \Closure ? $v() : $v;
            }
        };

        return new class($empty) {
            private $client;
            private $empty;

            public function __construct(object $empty)
            {
                $this->empty = $empty;
            }

            public function __call(string $method, array $args): mixed
            {
                $client = $this->client ??= \Illuminate\Support\Facades\Http::getFacadeRoot();
                if (in_array($method, ['get', 'post', 'put', 'patch', 'delete', 'head'])) {
                    try {
                        return $client->{$method}(...$args);
                    } catch (\Illuminate\Http\Client\ConnectionException $e) {
                        return $this->empty;
                    }
                }
                if ($method === 'pool') {
                    return $client->pool(...$args);
                }
                $this->client = $client->{$method}(...$args);
                return $this;
            }
        };
    }
}

if (!function_exists('storage')) {
    /**
     * storage
     *
     * @return \Illuminate\Support\Facades\Storage
     */
    function storage()
    {
        return \Illuminate\Support\Facades\Storage::getFacadeRoot();
    }
}

if (!function_exists('laravel_cookie')) {
    /**
     * laravel_cookie
     *
     * @return \Illuminate\Support\Facades\Cookie
     */
    function laravel_cookie()
    {
        return \Illuminate\Support\Facades\Cookie::getFacadeRoot();
    }
}

if (!function_exists('laravel_file')) {
    /**
     * laravel_file
     *
     * @return \Illuminate\Support\Facades\File
     */
    function laravel_file()
    {
        return \Illuminate\Support\Facades\File::getFacadeRoot();
    }
}
