<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Client\GitHubClient;
use App\Service\CompareService;
use PHPUnit\Framework\TestCase;

class CompareServiceTest extends TestCase
{
    public function testGetRepoName(): void
    {
        $service = $this->getService();

        $name = $service->getRepoName('https://github.com/symfony/symfony');
        $this->assertEquals('symfony/symfony', $name);

        $name = $service->getRepoName('symfony/symfony');
        $this->assertEquals('symfony/symfony', $name);
    }

    public function getService(): CompareService
    {
        /** @var GitHubClient $gitHubMock */
        $gitHubMock = $this->createMock((GitHubClient::class));

        return new CompareService($gitHubMock);
    }
}
