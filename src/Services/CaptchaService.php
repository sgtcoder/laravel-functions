<?php

namespace SgtCoder\LaravelFunctions\Services;

use Illuminate\Support\Facades\{
    Http,
    Log
};
use Illuminate\Validation\{
    Rule,
    ValidationException
};

class CaptchaService
{
    public function verify_captcha()
    {
        request()->validate(
            [
                'g-recaptcha-response' => 'required',
            ],
            [
                'g-recaptcha-response.required' => 'Captcha Required',
            ]
        );

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $fields = array(
            'secret' => config('captcha.private_key'),
            'response' => request('g-recaptcha-response'),
            'remoteip' => request()->getClientIp(),
        );

        $response = Http::asForm()->post($url, $fields)->json();

        if ($response['success']) {
            return true;
        }

        Log::channel('stack')->error('Captcha V3 Failed', $response);

        throw ValidationException::withMessages(['g-recaptcha-response' => 'Captcha Invalid']);
    }

    public function verify_captcha_v3()
    {
        // @phpstan-ignore-next-line
        $response = \GoogleReCaptchaV3::verifyResponse(
            request('g-recaptcha-response'),
            request()->getClientIp()
        );

        if ($response->isSuccess()) {
            return true;
        }

        Log::channel('stack')->error('Captcha V3 Failed: ' . $response->getMessage());

        throw ValidationException::withMessages(['g-recaptcha-response' => 'Captcha Invalid']);
    }

    public function turnstile()
    {
        request()->validate([
            // @phpstan-ignore-next-line
            'cf-turnstile-response' => ['required', Rule::turnstile()],
        ]);
    }

    public function honeypot()
    {
        request()->validate([
            'my_name'   => 'honeypot',
            'my_time'   => 'required|honeytime:5'
        ]);
    }
}
