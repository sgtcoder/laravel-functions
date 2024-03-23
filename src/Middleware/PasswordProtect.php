<?php

namespace SgtCoder\LaravelFunctions\Middleware;

use Closure;
use Cookie;
use Illuminate\Http\Request;

class PasswordProtect
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

        if (!$this->validateAccess()) {
            return response()->view('vendor.password-protect.login');
        }

        return $response;
    }

    public function validateAccess($login = false)
    {
        $token = ($login) ? md5(request('password_protect_password')) : Cookie::get('PASSWORD_PROTECT_AUTH_MD5');

        if ($token == config('password_protect.AUTH_MD5')) {
            if ($login) {
                Cookie::queue(Cookie::make('PASSWORD_PROTECT_AUTH_MD5', $token, (config('password_protect.AUTH_HOURS') * 60)));
            }

            return true;
        }

        return false;
    }

    public function login()
    {
        if ($this->validateAccess($login = true)) {
            return redirect()->back();
        }

        return redirect()->back()->with('error', 'Incorrect Password');
    }

    public function logout()
    {
        Cookie::queue(Cookie::forget('PASSWORD_PROTECT_AUTH_MD5'));

        return redirect()->back();
    }
}
