<?php

namespace SgtCoder\LaravelFunctions\Services;

use Illuminate\Support\Facades\Http;

class BrowserService
{
    private string $url;

    private $agents;

    public function __construct()
    {
        $this->url = 'https://microlink.io/user-agents.json';
    }

    public function get_latest_linux_chrome()
    {
        return $this->get_user_agents()
            ->sortDesc()
            ->first(fn($item) => str_contains($item, 'Mozilla/5.0 (X11; Linux x86_64)'));
    }

    public function get_random_browser()
    {
        return $this->get_user_agents()->random();
    }

    public function get_browsers()
    {
        return $this->get_user_agents()->sortDesc();
    }

    public function get_user_agents()
    {
        return collect($this->fetch_agents()['user'] ?? []);
    }

    public function get_crawler_agents()
    {
        return collect($this->fetch_agents()['crawler'] ?? []);
    }

    public function get_ai_agents()
    {
        return collect($this->fetch_agents()['ai'] ?? []);
    }

    public function fetch_agents()
    {
        if ($this->agents === null) {
            $this->agents = $this->api_call('', 'get');
        }

        return $this->agents;
    }

    public function api_call($path, $method_type = 'get', $payload = null)
    {
        $url = $this->url . $path;

        return Http::timeout(900)
            ->acceptJson()
            ->$method_type($url, $payload)
            ->throw()
            ->json();
    }
}
