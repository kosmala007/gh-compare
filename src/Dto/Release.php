<?php

declare(strict_types=1);

namespace App\Dto;

use DateTime;

class Release
{
    public function __construct(
        private int $id,
        private string $url,
        private string $name,
        private string $tagName,
        private DateTime $createdAt,
        private DateTime $publishedAt,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function tagName(): string
    {
        return $this->tagName;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function publishedAt(): DateTime
    {
        return $this->publishedAt;
    }
}
