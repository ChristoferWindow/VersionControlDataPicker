<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * Class GuzzleApiClient
 */
class GuzzleApiClient implements ApiClient
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $url
     * @param string $method
     * @return ResponseInterface
     * @throws InvalidResponse
     */
    public function query(string $url, string $method = 'GET'): ResponseInterface
    {
        return $this->client->request($method, $url);
    }
}