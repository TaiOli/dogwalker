<?php

namespace App\Enums;

enum TourStatus: string
{
    case PENDENTE = 'pendente';
    case ACEITO = 'aceito';
    case RECUSADO = 'recusado';
    case FINALIZADO = 'finalizado';
    case CANCELADO = 'cancelado';

    public function label(): string
    {
        return match ($this) {
            self::PENDENTE => 'Pendente',
            self::ACEITO => 'Aceito',
            self::RECUSADO => 'Recusado',
            self::FINALIZADO => 'Finalizado',
            self::CANCELADO => 'Cancelado',
        };
    }
}