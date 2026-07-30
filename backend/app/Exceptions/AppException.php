<?php

namespace App\Exceptions;

use Exception;

abstract class AppException extends Exception
{
    protected int $httpStatus = 400;

    public function httpStatus(): int
    {
        return $this->httpStatus;
    }
}