<?php

declare(strict_types=1);

namespace App\Client;

use App\Dto\Repo;
use App\Exception\ForbiddenRepoException;
use App\Exception\MovedPermanentlyRepoException;
use App\Exception\NotFoundRepoException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GitHubClient
{
    public const ENDPOINT = 'https://api.github.com/';
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => self::ENDPOINT]);
    }

    /**
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
            if ($e->getCode() == 301) {
                throw new MovedPermanentlyRepoException();
            }
            if ($e->getCode() == 404) {
                throw new NotFoundRepoException();
            }
            if ($e->getCode() == 403) {
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
}
