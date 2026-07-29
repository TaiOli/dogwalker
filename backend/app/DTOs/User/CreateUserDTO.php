<?php

namespace App\DTOs\User;

use App\Enums\TipoUsuario;

class CreateUserDTO
{
    public function __construct(
        public readonly string  $username,
        public readonly string  $nome,
        public readonly string  $email,
        public readonly string  $password,
        public readonly TipoUsuario  $tipoUsuario,
        public readonly ?string $telefone = null,
        public readonly mixed   $foto = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            username: $validated['username'],
            nome: $validated['nome'],
            email: $validated['email'],
            password: $validated['password'],
            tipoUsuario: TipoUsuario::from($validated['tipo_usuario']),
            telefone: $validated['telefone'] ?? null,
            foto: $validated['foto'] ?? null,
        );
    }
}