<?php

if (!function_exists('browser_service')) {
    /**
     * browser_service
     *
     * @return \SgtCoder\LaravelFunctions\Services\BrowserService
     */
    function browser_service()
    {
        return new \SgtCoder\LaravelFunctions\Services\BrowserService();
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
        return new \SgtCoder\LaravelFunctions\Services\CaptchaService();
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
        return new \SgtCoder\LaravelFunctions\Services\PasswordService();
    }
}
