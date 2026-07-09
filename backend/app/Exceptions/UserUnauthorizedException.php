<?php

namespace App\Exceptions;

use Exception;

class UserUnauthorizedException extends Exception
{
    protected $message = 'Usuário não encontrado para atualização';
}