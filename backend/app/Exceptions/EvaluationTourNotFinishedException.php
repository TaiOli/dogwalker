<?php

namespace App\Exceptions;

use Exception;

class EvaluationTourNotFinishedException extends Exception
{
    protected $message = 'Só é possível avaliar passeios finalizados';
}