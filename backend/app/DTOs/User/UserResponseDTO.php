<?php

namespace App\DTOs\User;

use App\Models\User;

class UserResponseDTO
{
    public readonly int     $id;
    public readonly string  $nome;
    public readonly string  $email;
    public readonly ?string $telefone;
    public readonly string  $tipoUsuario;
    public readonly ?string $foto;
    public readonly ?float  $mediaAvaliacao;

    public function __construct(User $user)
    {
        $this->id           = $user->id;
        $this->nome         = $user->nome;
        $this->email        = $user->email;
        $this->telefone     = $user->telefone;
        $this->tipoUsuario  = $user->tipo_usuario;
        $this->foto         = $user->foto;

        $this->mediaAvaliacao = isset($user->received_evaluations_avg_nota)
            ? round((float) $user->received_evaluations_avg_nota, 1)
            : null;
    }

    public function toArray(): array
    {
        return [
            'id'             => $this->id,
            'nome'           => $this->nome,
            'email'          => $this->email,
            'telefone'       => $this->telefone,
            'tipo_usuario'   => $this->tipoUsuario,
            'foto'           => $this->foto,
            'media_avaliacao' => $this->mediaAvaliacao,
        ];
    }

    public static function collection(iterable $users): array
    {
        return collect($users)
            ->map(fn(User $u) => (new self($u))->toArray())
            ->values()
            ->all();
    }
}