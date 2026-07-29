<?php

namespace App\Enums;

enum TipoUsuario: string
{
    case TUTOR = 'tutor';
    case PASSEADOR = 'passeador';

    public function label(): string
    {
        return match ($this) {
            self::TUTOR => 'Tutor',
            self::PASSEADOR => 'Passeador',
        };
    }
}