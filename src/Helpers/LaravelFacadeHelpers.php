<?php

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

if (!function_exists('laravel_storage')) {
    /**
     * laravel_storage
     *
     * @return \Illuminate\Support\Facades\Storage
     */
    function laravel_storage()
    {
        return \Illuminate\Support\Facades\Storage::getFacadeRoot();
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

if (!function_exists('laravel_artisan')) {
    /**
     * laravel_artisan
     *
     * @return \Illuminate\Support\Facades\Artisan
     */
    function laravel_artisan()
    {
        return \Illuminate\Support\Facades\Artisan::getFacadeRoot();
    }
}

if (!function_exists('laravel_http')) {
    /**
     * laravel_http
     *
     * @return \Illuminate\Support\Facades\Http
     */
    function laravel_http()
    {
        return \Illuminate\Support\Facades\Http::getFacadeRoot();
    }
}

if (!function_exists('laravel_url')) {
    /**
     * laravel_url
     *
     * @return \Illuminate\Support\Facades\URL
     */
    function laravel_url()
    {
        return \Illuminate\Support\Facades\URL::getFacadeRoot();
    }
}

// Aliases
if (!function_exists('artisan')) {
    /**
     * artisan
     *
     * @return \Illuminate\Support\Facades\Artisan
     */
    function artisan()
    {
        return laravel_artisan();
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
        return laravel_http();
    }
}
