<?php

if (!function_exists('browser_service')) {
    /**
     * browser_service
     *
     * @return \SgtCoder\LaravelFunctions\Services\BrowserService
     */
    function browser_service()
    {
        static $instance = null;

        if ($instance === null) {
            $instance = new \SgtCoder\LaravelFunctions\Services\BrowserService();
        }

        return $instance;
    }
}

if (!function_exists('captcha_service')) {
    /**
     * captcha_service
     *
     * @return \SgtCoder\LaravelFunctions\Services\CaptchaService
     */
    function captcha_service()
    {
        static $instance = null;

        if ($instance === null) {
            $instance = new \SgtCoder\LaravelFunctions\Services\CaptchaService();
        }

        return $instance;
    }
}

if (!function_exists('password_service')) {
    /**
     * password_service
     *
     * @return \SgtCoder\LaravelFunctions\Services\PasswordService
     */
    function password_service()
    {
        static $instance = null;

        if ($instance === null) {
            $instance = new \SgtCoder\LaravelFunctions\Services\PasswordService();
        }

        return $instance;
    }
}

if (!function_exists('media_service')) {
    /**
     * media_service
     *
     * @return object
     */
    function media_service()
    {
        static $instance = null;

        if ($instance === null) {
            // @phpstan-ignore-next-line
            $instance = new \App\Services\MediaService;
        }

        return $instance;
    }
}
