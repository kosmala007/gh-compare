<?php

declare(strict_types=1);

namespace App\Dto;

use DateTime;

class ComparedRepo
{
    public function __construct(
        private string $name,
        private string $url,
        private int $starsCount,
        private int $forksCount,
        private int $watchersCount,
        private ?DateTime $latestReleaseDate,
        private int $openPullRequestCount,
        private int $closedPullRequestCount,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getStarsCount(): int
    {
        return $this->starsCount;
    }

    public function getForksCount(): int
    {
        return $this->forksCount;
    }

    public function getWatchersCount(): int
    {
        return $this->watchersCount;
    }

    public function getLatestReleaseDate(): ?DateTime
    {
        return $this->latestReleaseDate;
    }

    public function getOpenPullRequestCount(): int
    {
        return $this->openPullRequestCount;
    }

    public function getClosedPullRequestCount(): int
    {
        return $this->closedPullRequestCount;
    }
}
