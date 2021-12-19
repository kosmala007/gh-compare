<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Client\GitHubClient;
use App\Dto\Release;
use App\Dto\Repo;
use App\Exception\InvalidPullStateException;
use App\Exception\NotFoundRepoException;
use App\Exception\NotFoundResourceException;
use PHPUnit\Framework\TestCase;

class GitHubClientTest extends TestCase
{
    public function testGetRepo(): void
    {
        $client = new GitHubClient();
        $repo = $client->getRepo('symfony/symfony');

        $this->assertInstanceOf(Repo::class, $repo);
    }

    public function testNotFoundRepo()
    {
        $this->expectException(NotFoundRepoException::class);

        $client = new GitHubClient();
        $client->getRepo('test/not-found');
    }

    public function testGetLatestRelease(): void
    {
        $client = new GitHubClient();
        $release = $client->getLatestRelease('symfony/symfony');

        $this->assertInstanceOf(Release::class, $release);
    }

    public function testNotFoundResourceWhenGetRelease(): void
    {
        $this->expectException(NotFoundResourceException::class);

        $client = new GitHubClient();
        $release = $client->getLatestRelease('test/not-found');
    }

    public function testRepoWithoutRelease(): void
    {
        $client = new GitHubClient();
        $release = $client->getLatestRelease('octocat/Hello-World');

        $this->assertEmpty($release);
    }

    public function testGetPullsCount(): void
    {
        $client = new GitHubClient();
        $count = $client->getCountPulls('symfony/symfony');

        $this->assertIsInt($count);
    }

    public function testGetOpenPullsCount(): void
    {
        $client = new GitHubClient();
        $count = $client->getCountPulls('symfony/symfony', GitHubClient::PULL_STATE_OPEN);

        $this->assertIsInt($count);
    }

    public function testGetClosePullsCount(): void
    {
        $client = new GitHubClient();
        $count = $client->getCountPulls('symfony/symfony', GitHubClient::PULL_STATE_CLOSE);

        $this->assertIsInt($count);
    }

    public function testInvalidPullState(): void
    {
        $this->expectException(InvalidPullStateException::class);

        $client = new GitHubClient();
        $release = $client->getCountPulls('test/not-found', 'invalid');
    }
}
