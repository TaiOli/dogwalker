<?php

namespace App\Exceptions;

class TourInvalidStatusException extends AppException
{
    protected int $httpStatus = 409;

    public function __construct(string $message = 'Status inválido para esta operação.')
    {
        parent::__construct($message);
    }
}