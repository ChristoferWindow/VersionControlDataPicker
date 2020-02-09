<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;

abstract class VersionControlAdapter
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $apiClient;

    /**
     * @var string
     */
    protected $fullUrl;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    protected $login;

    /**
     * @var string
     */
    protected $repo;

    /**
     * @var string
     */
    protected $branch;

    public function __construct(
        GuzzleHttp\Client $apiClient,
        string $login,
        string $repo,
        string $branch
    ) {
        $this->apiClient = $apiClient;
        $this->login = $login;
        $this->repo = $repo;
        $this->branch = $branch;
    }

    public function getLatestCommitSha(): string{}
    public function createUrl(VersionControlUrlCommand $command): string {}
    protected function parseResponse(ResponseInterface $response){}

    /**
     * @param string $method
     * @param string $url
     * @return ResponseInterface
     * @throws InvalidResponse
     */
    public function query(string $url, string $method = 'GET'): ResponseInterface
    {
        try {
            return $this->apiClient->request($method, $url);
        } catch (Exception $e) {
            throw new InvalidResponse($e->getMessage(), $e->getCode());
        }
    }
}
