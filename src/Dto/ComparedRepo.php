<?php

declare(strict_types=1);

namespace App\Dto;

use DateTime;
use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("none")
 */
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

    public function name(): string
    {
        return $this->name;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function starsCount(): int
    {
        return $this->starsCount;
    }

    public function forksCount(): int
    {
        return $this->forksCount;
    }

    public function watchersCount(): int
    {
        return $this->watchersCount;
    }

    public function latestReleaseDate(): ?DateTime
    {
        return $this->latestReleaseDate;
    }

    public function openPullRequestCount(): int
    {
        return $this->openPullRequestCount;
    }

    public function closedPullRequestCount(): int
    {
        return $this->closedPullRequestCount;
    }
}
