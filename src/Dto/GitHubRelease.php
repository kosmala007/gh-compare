<?php

declare(strict_types=1);

namespace App\Dto;

use DateTime;

class GitHubRelease
{
    public function __construct(
        private int $id,
        private string $url,
        private string $name,
        private string $tagName,
        private DateTime $createdAt,
        private DateTime $publishedAt,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTagName(): string
    {
        return $this->tagName;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getPublishedAt(): DateTime
    {
        return $this->publishedAt;
    }
}
