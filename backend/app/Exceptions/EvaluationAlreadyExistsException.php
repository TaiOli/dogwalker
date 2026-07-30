<?php

namespace App\Exceptions;

class EvaluationAlreadyExistsException extends AppException
{
    protected int $httpStatus = 409;

    public function __construct(string $message = 'Este passeio já foi avaliado por você!')
    {
        parent::__construct($message);
    }
}