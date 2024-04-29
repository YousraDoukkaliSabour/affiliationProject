<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class ZeroBounceService
{
    private $apiKey;
    private $client;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client([
            'base_uri' => 'https://api.zerobounce.net/v2/',
        ]);
    }

    public function validateEmail(string $email): bool
    {
        try {
            $response = $this->client->get("validate?api_key={$this->apiKey}&email={$email}");
            $data = json_decode($response->getBody(), true);
            return isset($data['status']) && $data['status'] === 'valid';
        } catch (ClientException $e) {
            // Handle the exception (e.g., log the error, return false)
            return false;
        }
    }
}