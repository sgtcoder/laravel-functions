<?php

namespace SgtCoder\LaravelFunctions\Services;

use Illuminate\Validation\Rule;

class PasswordService
{
    /**
     * Generator types reachable through the public endpoint.
     *
     * @var list<string>
     */
    private const TYPES = ['uuid', 'hex', 'bearer', 'sanctum', 'redis', 'string', 'salt', 'password', 'mac', 'number'];

    public function generate_password()
    {
        $validator = validator(request()->all(), [
            'type' => ['required', Rule::in(self::TYPES)],
            'length' => ['nullable', 'integer', 'min:4', 'max:512'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 422);
        }

        $validated = $validator->validated();
        $type = $validated['type'];

        return response()->json(isset($validated['length']) ? $this->$type($validated['length']) : $this->$type(), 200);
    }

    public function uuid($length = 36)
    {
        return str()->uuid();
    }

    public function hex($length = 16)
    {
        return str()->hex($length);
    }

    public function bearer($length = 40)
    {
        return str()->bearer($length);
    }

    public function sanctum($length = 48)
    {
        return str()->sanctum($length);
    }

    public function redis($length = 16)
    {
        return str()->redis($length);
    }

    public function string($length = 16)
    {
        return str()->random($length);
    }

    public function salt($length = 64)
    {
        return str()->salt($length);
    }

    public function password($length = 32)
    {
        return str()->safePassword($length);
    }

    public function mac($length = 17)
    {
        return str()->mac();
    }

    public function number($length = 8)
    {
        return str()->digits($length);
    }

    public function generate_mac_address($qty = 1, $html = false)
    {
        $macs = [];
        for ($i = 1; $i <= $qty; $i++) {
            $macs[] = str()->mac();
        }

        if ($html) {
            $macs = nl2br(implode('<br />', $macs));
        }

        return $macs;
    }
}
