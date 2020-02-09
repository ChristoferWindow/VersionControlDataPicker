<?php

declare(strict_types=1);

class GithubVersionControlAdapter extends VersionControlAdapter
{
    private $baseUrl = 'https://api.github.com/repos';
    private $branches = 'branches';

    public function __construct(
        \GuzzleHttp\Client $apiClient,
        string $login,
        string $repo,
        string $branch
    ) {
        parent::__construct($apiClient, $login, $repo, $branch);
    }
    public function createUrl(VersionControlUrlCommand $command): string
    {
        $s = '/';

       return $command->getBaseUrl()
           . $s . $command->getLogin()
           . $s . $command->getRepo()
           . $s . $this->branches
           . $s . $command->getBranch()
           ;
    }

    public function getLatestCommitSha(): string
    {
        $this->fullUrl = $this->createUrl(
            new VersionControlUrlCommand($this->baseUrl, $this->login, $this->repo, $this->branch)
        );

        $query = $this->query($this->fullUrl);

        var_dump($query->getBody()); die;
    }
}