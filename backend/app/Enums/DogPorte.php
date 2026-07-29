<?php

namespace App\Enums;

enum DogPorte: string
{
    case PEQUENO = 'pequeno';
    case MEDIO = 'medio';
    case GRANDE = 'grande';

    public function label(): string
    {
        return match ($this) {
            self::PEQUENO => 'Pequeno',
            self::MEDIO => 'Médio',
            self::GRANDE => 'Grande',
        };
    }
}