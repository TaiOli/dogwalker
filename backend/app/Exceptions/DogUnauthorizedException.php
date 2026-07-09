<?php

namespace App\Exceptions;

use Exception;

class DogUnauthorizedException extends Exception
{
    protected $message = 'Cachorro não encontrado para atualização';
}