<?php

declare(strict_types=1);

namespace App\Dto;

class Compare
{
    public function __construct(
        private ComparedRepo $first,
        private ComparedRepo $second,
    ) {}

    public function first(): ComparedRepo
    {
        return $this->first;
    }

    public function second(): ComparedRepo
    {
        return $this->second;
    }
}
