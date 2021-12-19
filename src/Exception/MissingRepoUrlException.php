<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class MissingRepoUrlException extends Exception
{
    public function __construct(string $name)
    {
        parent::__construct("Missing $name repo url or name");
    }
}
