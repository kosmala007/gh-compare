<?php

declare(strict_types=1);

namespace App\Service;

use App\Client\GitHubClient;
use App\Dto\Compare;
use App\Dto\ComparedRepo;
use App\Exception\ForbiddenRepoException;
use App\Exception\InvalidPullStateException;
use App\Exception\MovedPermanentlyRepoException;
use App\Exception\NotFoundRepoException;
use App\Exception\NotFoundResourceException;
use GuzzleHttp\Exception\GuzzleException;

class CompareService
{
    public function __construct(
        private GitHubClient $gitHubClient,
    ) {}

    public function compare(string $firstRepoUrl, string $secondRepoUrl): Compare
    {
        $firstGitHubRepo = $this->getComparedRepo($this->getRepoName($firstRepoUrl));
        $secondGitHubRepo = $this->getComparedRepo($this->getRepoName($secondRepoUrl));

        return new Compare(
            $firstGitHubRepo,
            $secondGitHubRepo,
        );
    }

    /**
     * @throws NotFoundResourceException
     * @throws NotFoundRepoException
     * @throws InvalidPullStateException
     * @throws MovedPermanentlyRepoException
     * @throws GuzzleException
     * @throws ForbiddenRepoException
     */
    public function getComparedRepo(string $name): ComparedRepo
    {
        $repo = $this->gitHubClient->getRepo($name);
        $release = $this->gitHubClient->getLatestRelease($name);
        $openPullRequestCount = $this->gitHubClient->getCountPulls($name, GitHubClient::PULL_STATE_OPEN);
        $closePullRequestCount = $this->gitHubClient->getCountPulls($name, GitHubClient::PULL_STATE_CLOSE);

        return new ComparedRepo(
            $repo->name(),
            $repo->url(),
            $repo->starsCount(),
            $repo->forksCount(),
            $repo->wathersCount(),
            $release ? $release->publishedAt() : null,
            $openPullRequestCount,
            $closePullRequestCount,
        );
    }

    public function getRepoName(string $repoUrl): string
    {
        return str_replace('https://github.com/', '', $repoUrl);
    }
}
