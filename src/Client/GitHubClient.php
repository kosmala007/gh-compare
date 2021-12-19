<?php

declare(strict_types=1);

namespace App\Client;

use App\Dto\Release;
use App\Dto\Repo;
use App\Exception\ForbiddenRepoException;
use App\Exception\InvalidPullStateException;
use App\Exception\MovedPermanentlyRepoException;
use App\Exception\NotFoundRepoException;
use App\Exception\NotFoundResourceException;
use DateTime;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GitHubClient
{
    public const PULL_STATE_OPEN = 'open';
    public const PULL_STATE_CLOSE = 'closed';

    public const ENDPOINT = 'https://api.github.com/';
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => self::ENDPOINT]);
    }

    /**
     * @see https://docs.github.com/en/rest/reference/repos#get-a-repository
     *
     * @throws GuzzleException
     * @throws MovedPermanentlyRepoException
     * @throws NotFoundRepoException
     * @throws ForbiddenRepoException
     */
    public function getRepo(string $repoName): Repo
    {
        try {
            $response = $this->client->get('repos/'.$repoName);
        } catch (GuzzleException $e) {
            if (301 == $e->getCode()) {
                throw new MovedPermanentlyRepoException();
            }
            if (404 == $e->getCode()) {
                throw new NotFoundRepoException();
            }
            if (403 == $e->getCode()) {
                throw new ForbiddenRepoException();
            }
            throw $e;
        }
        $data = json_decode($response->getBody()->getContents(), true);

        return new Repo(
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
     * @throws GuzzleException
     * @throws Exception
     */
    public function getLatestRelease(string $repoName): ?Release
    {
        try {
            $response = $this->client->get("repos/$repoName/releases", [
                'query' => [
                    'per_page' => 1,
                ],
            ]);
        } catch (GuzzleException $e) {
            if (404 == $e->getCode()) {
                throw new NotFoundResourceException();
            }

            throw $e;
        }
        $data = json_decode($response->getBody()->getContents(), true);
        if (!isset($data[0])) {
            return null;
        }
        $data = $data[0];

        return new Release(
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
            $response = $this->client->get('search/issues', [
                'query' => 'per_page=1&q='.implode('+', $querySearch),
            ]);
        } catch (GuzzleException $e) {
            if (404 == $e->getCode()) {
                throw new NotFoundResourceException();
            }

            throw $e;
        }
        $data = json_decode($response->getBody()->getContents(), true);

        return $data['total_count'];
    }
}
