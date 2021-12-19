<?php

declare(strict_types=1);

namespace App\Dto;

class Repo
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

    public function name(): string
    {
        return $this->name;
    }

    public function fullName(): string
    {
        return $this->fullName;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function forksCount(): int
    {
        return $this->forksCount;
    }

    public function starsCount(): int
    {
        return $this->starsCount;
    }

    public function wathersCount(): int
    {
        return $this->wathersCount;
    }
}
