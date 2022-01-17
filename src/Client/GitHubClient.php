<?php

declare(strict_types=1);

namespace App\Client;

use App\Dto\GitHubRelease;
use App\Dto\GitHubRepo;
use App\Exception\ForbiddenRepoException;
use App\Exception\InvalidPullStateException;
use App\Exception\MovedPermanentlyRepoException;
use App\Exception\NotFoundRepoException;
use App\Exception\NotFoundResourceException;
use DateTime;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GitHubClient implements Client
{
    public const PULL_STATE_OPEN = 'open';
    public const PULL_STATE_CLOSE = 'closed';
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $githubClient)
    {
        $this->client = $githubClient;
    }

    /**
     * @see https://docs.github.com/en/rest/reference/repos#get-a-repository
     *
     * @throws MovedPermanentlyRepoException
     * @throws NotFoundRepoException
     * @throws ForbiddenRepoException
     * @throws ExceptionInterface
     */
    public function getRepo(string $repoName): GitHubRepo
    {
        try {
            $response = $this->client->request('GET', 'repos/'.$repoName);
            $data = $response->toArray();
        } catch (ExceptionInterface $e) {
            switch ($e->getCode()) {
                case 301:
                    throw new MovedPermanentlyRepoException();
                case 404:
                    throw new NotFoundRepoException();
                case 403:
                    throw new ForbiddenRepoException();
            }
            throw $e;
        }

        return new GitHubRepo(
            $data['id'],
            $data['name'],
            $data['full_name'],
            $data['url'],
            $data['description'],
            $data['forks_count'],
            $data['stargazers_count'],
            $data['watchers_count'],
        );
    }

    /**
     * @see https://docs.github.com/en/rest/reference/releases#list-releases
     *
     * @throws NotFoundResourceException
     * @throws ExceptionInterface
     */
    public function getLatestRelease(string $repoName): ?GitHubRelease
    {
        try {
            $response = $this->client->request('GET', "repos/$repoName/releases", [
                'query' => [
                    'per_page' => 1,
                ],
            ]);
            $data = $response->toArray();
        } catch (ExceptionInterface $e) {
            switch ($e->getCode()) {
                case 404:
                    throw new NotFoundResourceException();
            }
            throw $e;
        }
        if (!isset($data[0])) {
            return null;
        }
        $data = $data[0];

        return new GitHubRelease(
            $data['id'],
            $data['url'],
            $data['name'],
            $data['tag_name'],
            new DateTime($data['created_at']),
            new DateTime($data['published_at']),
        );
    }

    /**
     * @see https://docs.github.com/en/search-github/searching-on-github/searching-issues-and-pull-requests#search-only-issues-or-pull-requests
     * @see https://docs.github.com/en/rest/reference/search#constructing-a-search-query
     *
     * @throws InvalidPullStateException
     * @throws NotFoundResourceException
     * @throws ExceptionInterface
     */
    public function getCountPulls(string $repoName, string $state = null): int
    {
        $querySearch = [
            'repo:'.$repoName,
            'is:pr',
        ];

        if ($state) {
            if (!in_array($state, [self::PULL_STATE_OPEN, self::PULL_STATE_CLOSE])) {
                throw new InvalidPullStateException();
            }
            $querySearch[] = 'state:'.$state;
        }

        try {
            $response = $this->client->request('GET', 'search/issues?per_page=1&q='.implode('+', $querySearch));
            $data = $response->toArray();
        } catch (ExceptionInterface $e) {
            switch ($e->getCode()) {
                case 404:
                    throw new NotFoundResourceException();
            }
            throw $e;
        }

        return $data['total_count'] ?? 0;
    }
}
