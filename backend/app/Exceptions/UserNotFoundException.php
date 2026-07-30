<?php

namespace App\Exceptions;;

class UserNotFoundException extends AppException
{

    protected int $httpStatus = 404;

    public function __construct(string $message = 'Cachorro não encontrado!')
    {
        parent::__construct($message);
    }
}
