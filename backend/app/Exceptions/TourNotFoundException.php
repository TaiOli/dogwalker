<?php

namespace App\Exceptions;

class TourNotFoundException extends AppException
{
    protected int $httpStatus = 404;

    public function __construct(string $message = 'Passeio não encontrado!')
    {
        parent::__construct($message);
    }
}