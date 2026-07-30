<?php

namespace App\Exceptions;

class DogUnauthorizedException extends AppException
{
    protected int $httpStatus = 403;

    public function __construct(string $message = 'Cachorro não encontrado para atualização!')
    {
        parent::__construct($message);
    }
}