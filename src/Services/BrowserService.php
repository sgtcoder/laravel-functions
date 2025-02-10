<?php

namespace SgtCoder\LaravelFunctions\Services;

use Illuminate\Support\Facades\Http;

class BrowserService
{
    private string $url;

    public function __construct()
    {
        $this->url = 'https://raw.githubusercontent.com/jnrbsn/user-agents/main/user-agents.json';
    }

    public function get_latest_linux_chrome()
    {
        $path = '';

        $payload = [];

        $data = $this->api_call($path, $method_type = 'get', $payload);

        $data = $data->sortDesc()->filter(function ($item) {
            if (strpos($item, 'Mozilla/5.0 (X11; Linux x86_64)') !== false) {
                return true;
            }
        });

        return $data->first();
    }

    public function get_random_browser()
    {
        $path = '';

        $payload = [];

        $data = $this->api_call($path, $method_type = 'get', $payload);

        $data = $data->random();

        return $data;
    }

    public function get_browsers()
    {
        $path = '';

        $payload = [];

        $data = $this->api_call($path, $method_type = 'get', $payload);

        return $data->sortDesc();
    }

    public function api_call($path, $method_type = 'get', $payload = null)
    {
        $api_key = config('settings.browser.api_key');
        $headers = [];

        $url = $this->url . $path;

        $data = Http::timeout(900)->withHeaders($headers)->$method_type($url, $payload)->collect();

        return $data;
    }
}
