<?php

declare(strict_types=1);

namespace VersionControl;

use ApiClient;
use Psr\Http\Message\ResponseInterface;

/**
 * Class VersionControlAdapter
 * @package VersionControl
 */
abstract class VersionControlAdapter
{
    /**
     * @var ApiClient
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
        ApiClient $apiClient,
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
    public function query(string $url, string $method = 'GET'): ResponseInterface{}
}
