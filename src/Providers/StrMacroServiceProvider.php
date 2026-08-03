<?php

namespace SgtCoder\LaravelFunctions\Providers;

use Illuminate\Support\{
    ServiceProvider as BaseServiceProvider,
    Str
};

class StrMacroServiceProvider extends BaseServiceProvider
{
    /**
     * Register the random string generator macros.
     *
     * Every generator draws from a CSPRNG (random_bytes/random_int) so the
     * output is safe to use for keys, tokens and passwords.
     *
     * @return void
     */
    public function register()
    {
        Str::macro('hex', function (int $length = 16): string {
            return (string) str()->of(bin2hex(random_bytes((int) ceil($length / 2))))
                ->substr(0, $length)
                ->upper();
        });

        Str::macro('bearer', function (int $length = 40): string {
            return Str::random($length);
        });

        Str::macro('redis', function (int $length = 16): string {
            return Str::random($length);
        });

        Str::macro('salt', function (int $length = 64): string {
            return Str::password($length);
        });

        // Str::password() is a real method, so this variant needs its own name.
        // Swaps shell/URL hostile characters and guarantees a leading letter.
        Str::macro('safePassword', function (int $length = 32): string {
            do {
                $password = str_replace(['\\', '#'], ['/', '!'], Str::password($length));
            } while (!preg_match('/^[A-Za-z]/', $password));

            return $password;
        });

        Str::macro('mac', function (): string {
            return fake()->macAddress();
        });

        Str::macro('digits', function (int $length = 8): string {
            $number = '';

            for ($i = 0; $i < $length; $i++) {
                $number .= random_int(0, 9);
            }

            return $number;
        });
    }
}
