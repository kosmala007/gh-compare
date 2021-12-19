<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class NotFoundResourceException extends Exception
{
    public function __construct()
    {
        parent::__construct('Not found resource');
    }
}
