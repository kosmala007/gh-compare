<?php

declare(strict_types=1);

namespace App\Exception;

use App\Client\GitHubClient;
use Exception;

class InvalidPullStateException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid pull request state. Possible statuses: ["'.GitHubClient::PULL_STATE_OPEN.'", "'.GitHubClient::PULL_STATE_CLOSE.'"]');
    }
}
