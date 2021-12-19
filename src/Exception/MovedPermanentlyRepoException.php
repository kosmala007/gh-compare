<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class MovedPermanentlyRepoException extends Exception
{
    public function __construct()
    {
        parent::__construct('Repository moved permanently');
    }
}
