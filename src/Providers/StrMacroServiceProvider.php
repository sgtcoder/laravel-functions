<?php

namespace SgtCoder\LaravelFunctions\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

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
        str()->macro('hex', function (int $length = 16): string {
            return (string) str()->of(bin2hex(random_bytes((int) ceil($length / 2))))
                ->substr(0, $length)
                ->upper();
        });

        str()->macro('bearer', function (int $length = 40): string {
            return str()->random($length);
        });

        // Sanctum's token shape: entropy plus an 8 character crc32b checksum for
        // secret scanners. Any prefix sits outside $length.
        str()->macro('sanctum', function (int $length = 48, string $prefix = ''): string {
            $entropy = str()->random(max(8, $length - 8));

            return $prefix . $entropy . hash('crc32b', $entropy);
        });

        str()->macro('redis', function (int $length = 16): string {
            return str()->random($length);
        });

        str()->macro('salt', function (int $length = 64): string {
            return str()->password($length);
        });

        // Str::password() is a real method, so this variant needs its own name.
        // Swaps shell/URL hostile characters and guarantees a leading letter.
        str()->macro('safePassword', function (int $length = 32): string {
            do {
                $password = str_replace(['\\', '#'], ['/', '!'], str()->password($length));
            } while (!preg_match('/^[A-Za-z]/', $password));

            return $password;
        });

        str()->macro('mac', function (): string {
            return fake()->macAddress();
        });

        str()->macro('digits', function (int $length = 8): string {
            $number = '';

            for ($i = 0; $i < $length; $i++) {
                $number .= random_int(0, 9);
            }

            return $number;
        });
    }
}
