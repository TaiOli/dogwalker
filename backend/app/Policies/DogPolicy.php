<?php

namespace App\Policies;

use App\Models\Dog;
use App\Models\User;

class DogPolicy
{
    /**
     * Só o tutor dono do cachorro pode editar.
     */
    public function update(User $user, Dog $dog): bool
    {
        return $dog->user_id === $user->id;
    }

    /**
     * Só o tutor dono do cachorro pode excluir.
     */
    public function delete(User $user, Dog $dog): bool
    {
        return $dog->user_id === $user->id;
    }
}