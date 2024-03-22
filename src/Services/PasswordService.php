<?php

namespace SgtCoder\LaravelFunctions\Services;

class PasswordService
{
    public function generate_password()
    {
        $type = request('type');
        $length = request('length');

        if (empty($type)) return response()->json(['status' => false, 'message' => 'You must pass a type'], 422);

        return response()->json($this->$type($length), 200);
    }

    public function uuid($length = 36)
    {
        return str()->uuid();
    }

    public function hex($length = 16)
    {
        $token = hash('sha256', str()->random($length));
        $token = str()->of($token)->substr(0, $length);
        $token = str()->of($token)->upper();

        return $token;
    }

    public function bearer($length = 40)
    {
        return str()->random($length);
    }

    public function redis($length = 16)
    {
        return str()->random($length);
    }

    public function string($length = 16)
    {
        return str()->random($length);
    }

    public function salt($length = 64)
    {
        return str()->password($length);
    }

    public function password($length = 32)
    {
        $is_valid = false;

        while (!$is_valid) {
            $password = str()->password($length);
            $password = str()->replace('\\', '/', $password);

            $is_valid = str()->of($password)->substr(0, 1)->isMatch('/[A-Za-z]/');
        }

        return $password;
    }

    public function mac($length = 17)
    {
        return implode('', $this->generate_mac_address());
    }

    public function number($length = 8)
    {
        $number = null;
        for ($i = 0; $i < $length; $i++) {
            $number .= mt_rand(0, 9);
        }

        return $number;
    }

    public function generate_mac_address($qty = 1, $html = false)
    {
        // @phpstan-ignore-next-line
        $MacAddress = new \BlakeGardner\MacAddress;

        $macs = [];
        for ($i = 1; $i <= $qty; $i++) {
            // @phpstan-ignore-next-line
            $macs[] = $MacAddress->generateMacAddress();
        }

        if ($html) {
            $macs = nl2br(implode('<br />', $macs));
        }

        return $macs;
    }
}
