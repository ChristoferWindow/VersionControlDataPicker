<?php

declare(strict_types=1);

namespace VersionControl;

use Psr\Http\Message\ResponseInterface;

/**
 * Class GithubVersionControlAdapter
 * @package VersionControl
 */
class GithubVersionControlAdapter extends VersionControlAdapter
{
    private $baseUrl = 'https://api.github.com/repos';
    private $branches = 'branches';

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
        $fullUrl = $this->createUrl(
            new VersionControlUrlCommand($this->baseUrl, $this->login, $this->repo, $this->branch)
        );
        $query = $this->apiClient->query($fullUrl);
        $parsedResponse = $this->parseResponse($query);

        if (empty($parsedResponse['commit']['sha'])) {
            throw new \InvalidResponse('We could not fetch latest commit sha');
        }

        return $parsedResponse['commit']['sha'];
    }

    public function parseResponse(ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();
        $parsedResponse = json_decode($response->getBody()->getContents(), true);

        switch ($statusCode) {
            case 200:
                return $parsedResponse;
                break;
            case 404:
                throw new \InvalidResponse('Repository ' . $this->repo . ' '. mb_strtolower($parsedResponse['message']));
                break;
            case 500:
                throw new \InvalidResponse('There was a problem with connecting to the server');
                break;
            default:
                throw new \InvalidResponse('We could not process your request');
                break;
        }
    }
}