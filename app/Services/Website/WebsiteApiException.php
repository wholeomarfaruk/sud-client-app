<?php

namespace App\Services\Website;

use Exception;

class WebsiteApiException extends Exception
{
    public function __construct(string $message, protected int $statusCode = 0)
    {
        parent::__construct($message);
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }
}
