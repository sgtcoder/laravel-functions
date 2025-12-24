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
