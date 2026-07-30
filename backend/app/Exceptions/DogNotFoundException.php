<?php

namespace App\Exceptions;

class DogNotFoundException extends AppException
{
    protected int $httpStatus = 404;

    public function __construct(string $message = 'Usuário não encontrado!')
    {
        parent::__construct($message);
    }
}