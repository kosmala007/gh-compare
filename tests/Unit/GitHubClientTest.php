<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Client\GitHubClient;
use App\Dto\Repo;
use App\Exception\NotFoundRepoException;
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
}
