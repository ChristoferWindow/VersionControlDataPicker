<?php

declare(strict_types=1);

namespace VersionControl;

use ApiClient;

/**
 * Class VersionControlAdapterFactory
 * @package VersionControl
 */
class VersionControlAdapterFactory
{
    /**
     * @var ApiClient $apiClient
     */
    private $apiClient;
    /**
     * @var string
     */
    private $login;
    /**
     * @var string
     */
    private $repo;
    /**
     * @var string
     */
    private $branch;

    /**
     * VersionControlAdapterFactory constructor.
     * @param ApiClient $apiClient
     * @param string $login
     * @param string $repo
     * @param string $branch
     */
    public function __construct(ApiClient $apiClient, string $login, string $repo, string $branch)
    {
        $this->apiClient = $apiClient;
        $this->login = $login;
        $this->repo = $repo;
        $this->branch = $branch;
    }

    public function createByName(string $name): VersionControlAdapter
    {
        switch ($name) {
            case 'github':
                return new GithubVersionControlAdapter($this->apiClient, $this->login, $this->repo, $this->branch);
        }
    }
}