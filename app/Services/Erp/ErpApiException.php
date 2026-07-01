<?php

namespace App\Services\Erp;

use Exception;

class ErpApiException extends Exception
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
