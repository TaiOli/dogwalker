<?php

namespace App\Policies;

use App\Models\Tour;
use App\Models\User;
use App\Enums\TipoUsuario;

class TourPolicy
{
    /**
     * Só o tutor que solicitou pode cancelar.
     */
    public function cancel(User $user, Tour $tour): bool
    {
        return $tour->tutor_id === $user->id;
    }

    /**
     * Só o passeador responsável pode finalizar.
     */
    public function complete(User $user, Tour $tour): bool
    {
        return $tour->passeador_id === $user->id;
    }

    /**
     * Só o tutor que solicitou pode remover.
     */
    public function delete(User $user, Tour $tour): bool
    {
        return $tour->tutor_id === $user->id;
    }

    /**
     * Só passeadores podem aceitar passeios,
     * e se o passeio foi direcionado a alguém específico, só esse alguém pode aceitar.
     */
    public function accept(User $user, Tour $tour): bool
    {
        if ($user->tipo_usuario !== TipoUsuario::PASSEADOR) {
            return false;
        }

        return $tour->passeador_id === null || $tour->passeador_id === $user->id;
    }

    public function evaluate(User $user, Tour $tour, string $tipoAvaliador): bool
    {
        return match ($tipoAvaliador) {
            'tutor'     => $tour->tutor_id === $user->id,
            'passeador' => $tour->passeador_id === $user->id,
            default     => false,
        };
    }
}