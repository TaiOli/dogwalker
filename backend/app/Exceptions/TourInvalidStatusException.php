<?php
namespace App\Exceptions;

use Exception;

class TourInvalidStatusException extends Exception
{
    public function __construct(string $message = 'Este passeio não pode receber esta ação no status atual')
    {
        parent::__construct($message);
    }
}