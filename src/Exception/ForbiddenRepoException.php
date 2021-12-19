<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class ForbiddenRepoException extends Exception
{
    public function __construct()
    {
        parent::__construct('Forbidden repository');
    }
}
