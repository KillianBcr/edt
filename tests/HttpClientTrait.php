<?php
declare(encoding=1);

namespace App\Tests;

use GuzzleHttp\Client;
trait HttpClientTrait
{
    protected $httpClient;

    protected function setUpHttpClient(): void
    {
        $baseUri = $_ENV['API_BASE_URI'] ?: 'http://127.0.0.1:8000/api/';
        $this->httpClient = new Client([
            'base_uri' => $baseUri,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);
    }
}