<?php

declare(strict_types=1);

namespace src;

use ApiClient;
use GuzzleHttp\Client;

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
     * @return \Psr\Http\Message\ResponseInterface
     * @throws InvalidResponse
     */
    public function query(string $url, string $method = 'GET'): \Psr\Http\Message\ResponseInterface
    {
        try {
            return $this->client->request($method, $url);
        } catch (Exception $e) {
            throw new InvalidResponse($e->getMessage(), $e->getCode());
        }
    }
}