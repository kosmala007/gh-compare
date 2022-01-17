<?php

declare(strict_types=1);

namespace App\Dto;

class GitHubRepo
{
    public function __construct(
        private int $id,
        private string $name,
        private string $fullName,
        private string $url,
        private string $description,
        private int $forksCount,
        private int $starsCount,
        private int $wathersCount,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getForksCount(): int
    {
        return $this->forksCount;
    }

    public function getStarsCount(): int
    {
        return $this->starsCount;
    }

    public function getWathersCount(): int
    {
        return $this->wathersCount;
    }
}
