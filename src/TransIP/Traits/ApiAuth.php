<?php

namespace Marshmallow\Domain\TransIP\Traits;

use Illuminate\Support\Facades\Http;

trait ApiAuth
{
    /**
     * The label for the new access token
     * @var string
     */
    private $label = '';

    /**
     * @var string
     */
    private $signature;

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Creates a new Access Token
     *
     * @return string
     * @throws Exception
     */
    public function createToken()
    {
        $requestBody = $this->getAuthRequestBody();

        // Create signature using the JSON encoded request body and your private key.
        $this->signature = $this->createSignature($requestBody);

        $responseArray = $this->performRequest($requestBody);

        if (!array_key_exists('token', $responseArray)) {
            throw new \RuntimeException("An error occurred: {$responseArray['error']}");
        }

        return $responseArray['token'];
    }

    /**
     * Creates a JSON encoded string of the request body
     *
     * @return array
     */
    private function getAuthRequestBody()
    {
        return [
            'login' => config('trans-ip.login'),
            'nonce' => uniqid(),
            'read_only' => config('trans-ip.read_only'),
            'expiration_time' => config('trans-ip.expiration_time'),
            'label' => $this->label,
            'global_key' => config('trans-ip.global_key'),
        ];

        return json_encode($requestBody);
    }

    /**
     * @param string $requestBody
     * @return array
     */
    private function performRequest($requestArray)
    {
        $response = Http::withHeaders(
            [
                'Content-Type' => 'application/json',
                'Signature' => $this->signature,
            ]
        )->post($this->getAuthUrl(), $requestArray);

        return $response->json();
    }

    /**
     * Method for creating a signature based on
     * Same sign method as used in SOAP API.
     *
     * @param string $parameters
     * @return string
     * @throws Exception
     */
    private function createSignature($parameters)
    {
        // Fixup our private key, copy-pasting the key might lead to whitespace faults
        if (!preg_match(
            '/-----BEGIN (RSA )?PRIVATE KEY-----(.*)-----END (RSA )?PRIVATE KEY-----/si',
            $this->getPrivateKey(),
            $matches
        )
        ) {
            throw new \RuntimeException('Could not find a valid private key');
        }

        $key = $matches[2];
        $key = preg_replace('/\s*/s', '', $key);
        $key = chunk_split($key, 64, "\n");

        $key = "-----BEGIN PRIVATE KEY-----\n" . $key . "-----END PRIVATE KEY-----";
        if (!@openssl_sign(json_encode($parameters), $signature, $key, OPENSSL_ALGO_SHA512)) {
            throw new \RuntimeException(
                'The provided private key is invalid'
            );
        }

        return base64_encode($signature);
    }

    protected function getPrivateKey()
    {
        return file_get_contents($this->keyPath());
    }

    public function keyPath()
    {
        return storage_path(config('trans-ip.private_key_file_name'));
    }
}
