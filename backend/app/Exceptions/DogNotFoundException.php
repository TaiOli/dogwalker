<?php

namespace App\Exceptions;

use Exception;

class DogNotFoundException extends Exception
{
    protected $message = 'Usuário não encontrado';
}