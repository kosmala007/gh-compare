<?php

declare(strict_types=1);

namespace App\Dto;

class Release
{
    public function __construct(
        private int $id,
        private string $url,
        private string $name,
        private string $tagName,
        private \DateTime $createdAt,
        private \DateTime $publishedAt,
    ) {}
}
