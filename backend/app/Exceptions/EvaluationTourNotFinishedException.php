<?php

namespace App\Exceptions;

class EvaluationTourNotFinishedException extends AppException
{
    protected int $httpStatus = 409;

    public function __construct(string $message = 'Só é possível avaliar passeios finalizados!')
    {
        parent::__construct($message);
    }
}