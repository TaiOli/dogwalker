<?php
namespace App\Exceptions;

use Exception;

class TourUnauthorizedException extends Exception
{
    public function __construct(string $message = 'Você não tem permissão para realizar esta ação neste passeio.')
    {
        parent::__construct($message);
    }
}