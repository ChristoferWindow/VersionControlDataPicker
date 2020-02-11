<?php

declare(strict_types=1);

namespace VersionControl;

/**
 * Class VersionControlUrlCommand
 * @package VersionControl
 */
class VersionControlUrlCommand
{
    /**
     * @var string
     */
    private $baseUrl;
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
     * VersionControlUrlCommand constructor.
     *
     * @param string $baseUrl
     * @param string $login
     * @param string $repo
     * @param string $branch
     */
    public function __construct(string $baseUrl, string $login, string $repo, string $branch)
    {
        $this->baseUrl = $baseUrl;
        $this->login = $login;
        $this->repo = $repo;
        $this->branch = $branch;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getRepo(): string
    {
        return $this->repo;
    }

    /**
     * @return string
     */
    public function getBranch(): string
    {
        return $this->branch;
    }
}