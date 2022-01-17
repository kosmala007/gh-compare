<?php

declare(strict_types=1);

namespace App\Client;

use App\Dto\GitHubRelease;
use App\Dto\GitHubRepo;

interface Client
{
    public function getRepo(string $repoName): GitHubRepo;

    public function getLatestRelease(string $repoName): ?GitHubRelease;

    public function getCountPulls(string $repoName, string $state = null): int;
}
