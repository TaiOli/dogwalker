<?php

namespace App\DTOs\User;

class CreateUserDTO
{
    public function __construct(
        public readonly string  $nome,
        public readonly string  $email,
        public readonly string  $password,
        public readonly string  $tipoUsuario,
        public readonly ?string $telefone = null,
        public readonly mixed   $foto = null, 
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            nome:        $validated['nome'],
            email:       $validated['email'],
            password:    $validated['password'],
            tipoUsuario: $validated['tipo_usuario'],
            telefone:    $validated['telefone'] ?? null,
            foto:        $validated['foto'] ?? null,
        );
    }
}