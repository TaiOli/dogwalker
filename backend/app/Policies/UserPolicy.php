<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Só o próprio usuário pode editar seu perfil.
     */
    public function update(User $authUser, User $user): bool
    {
        return $authUser->id === $user->id;
    }
}