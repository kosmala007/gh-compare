<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Client\GitHubClient;
use App\Dto\GitHubRelease;
use App\Dto\GitHubRepo;
use App\Exception\InvalidPullStateException;
use App\Exception\NotFoundRepoException;
use App\Exception\NotFoundResourceException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class GitHubClientTest extends WebTestCase
{
    public function testGetRepo(): void
    {
        $response = json_encode([
            'id' => 458058,
            'name' => 'symfony',
            'full_name' => 'symfony/symfony',
            'url' => 'https://api.github.com/repos/symfony/symfony',
            'description' => 'The Symfony PHP framework',
            'forks_count' => 8505,
            'stargazers_count' => 26341,
            'watchers_count' => 26341,
        ]);
        $mockResponse = new MockResponse($response, [
            'http_code' => 200,
        ]);
        $mockHttpClient = new MockHttpClient($mockResponse);
        $client = new GitHubClient($mockHttpClient);
        $repo = $client->getRepo('symfony/symfony');

        $this->assertInstanceOf(GitHubRepo::class, $repo);
    }

    public function testNotFoundRepo()
    {
        $this->expectException(NotFoundRepoException::class);

        $mockResponse = new MockResponse(json_encode(null), [
            'http_code' => 404,
        ]);
        $mockHttpClient = new MockHttpClient($mockResponse);
        $client = new GitHubClient($mockHttpClient);
        $client->getRepo('test/not-found');
    }

    public function testGetLatestRelease(): void
    {
        $response = [[
            'id' => 56140427,
            'url' => 'https://api.github.com/repos/symfony/symfony/releases/56140427',
            'name' => 'v6.0.2',
            'tag_name' => 'v6.0.2',
            'created_at' => '2021-12-29T14:07:47Z',
            'published_at' => '2021-12-29T14:14:15Z',
        ]];
        $mockResponse = new MockResponse(json_encode($response), [
            'http_code' => 200,
        ]);
        $mockHttpClient = new MockHttpClient($mockResponse);
        $client = new GitHubClient($mockHttpClient);
        $release = $client->getLatestRelease('symfony/symfony');

        $this->assertInstanceOf(GitHubRelease::class, $release);
    }

    public function testNotFoundResourceWhenGetRelease(): void
    {
        $this->expectException(NotFoundResourceException::class);

        $mockResponse = new MockResponse(json_encode(null), [
            'http_code' => 404,
        ]);
        $mockHttpClient = new MockHttpClient($mockResponse);
        $client = new GitHubClient($mockHttpClient);
        $release = $client->getLatestRelease('test/not-found');
    }

    public function testRepoWithoutRelease(): void
    {
        $mockResponse = new MockResponse(json_encode([]), [
            'http_code' => 200,
        ]);
        $mockHttpClient = new MockHttpClient($mockResponse);
        $client = new GitHubClient($mockHttpClient);
        $release = $client->getLatestRelease('octocat/Hello-World');

        $this->assertEmpty($release);
    }

    public function testGetPullsCount(): void
    {
        $response = [
            'total_count' => 100,
        ];
        $mockResponse = new MockResponse(json_encode($response), [
            'http_code' => 200,
        ]);
        $mockHttpClient = new MockHttpClient($mockResponse);
        $client = new GitHubClient($mockHttpClient);
        $count = $client->getCountPulls('symfony/symfony');

        $this->assertIsInt($count);
    }

    public function testGetOpenPullsCount(): void
    {
        $response = [
            'total_count' => 100,
        ];
        $mockResponse = new MockResponse(json_encode($response), [
            'http_code' => 200,
        ]);
        $mockHttpClient = new MockHttpClient($mockResponse);
        $client = new GitHubClient($mockHttpClient);
        $count = $client->getCountPulls('symfony/symfony', GitHubClient::PULL_STATE_OPEN);

        $this->assertIsInt($count);
    }

    public function testGetClosePullsCount(): void
    {
        $response = [
            'total_count' => 100,
        ];
        $mockResponse = new MockResponse(json_encode($response), [
            'http_code' => 200,
        ]);
        $mockHttpClient = new MockHttpClient($mockResponse);
        $client = new GitHubClient($mockHttpClient);
        $count = $client->getCountPulls('symfony/symfony', GitHubClient::PULL_STATE_CLOSE);

        $this->assertIsInt($count);
    }

    public function testInvalidPullState(): void
    {
        $this->expectException(InvalidPullStateException::class);

        $mockHttpClient = new MockHttpClient();
        $client = new GitHubClient($mockHttpClient);
        $release = $client->getCountPulls('test/not-found', 'invalid');
    }
}
