<?php

namespace App\Exceptions;

use Exception;

class EvaluationAlreadyExistsException extends Exception
{
    protected $message = 'Este passeio já foi avaliado por você';
}