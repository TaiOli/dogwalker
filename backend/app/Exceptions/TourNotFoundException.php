<?php

namespace App\Exceptions;

use Exception;

class TourNotFoundException extends Exception
{
    protected $message = 'Passeio não encontrado';
}