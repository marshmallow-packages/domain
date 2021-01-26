<?php

namespace Marshmallow\Domain\TransIP\Traits;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

trait ApiMethods
{
    public function get(string $path): Response
    {
        $path = $this->getEndpointUrl($path);
        return Http::withToken($this->createToken())
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ])
                    ->get($path);
    }

    public function post(string $path, array $data = []): Response
    {
        $path = $this->getEndpointUrl($path);
        return Http::withToken($this->createToken())
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ])
                    ->post($path, $data);
    }

    /**
     * @return string
     */
    private function getEndpointUrl($path)
    {
        return sprintf(config('trans-ip.endpoint_url'), config('trans-ip.endpoint'), config('trans-ip.version'), $path);
    }

    /**
     * @return string
     */
    private function getAuthUrl()
    {
        return sprintf(config('trans-ip.auth_url'), config('trans-ip.endpoint'), config('trans-ip.version'));
    }
}
