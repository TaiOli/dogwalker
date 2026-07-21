<?php

namespace App\DTOs\User;

use Illuminate\Http\UploadedFile;

class UpdateUserDTO
{
    public function __construct(
        public readonly ?string       $nome = null,
        public readonly ?string       $email = null,
        public readonly ?string       $password = null,
        public readonly ?string       $telefone = null,
        public readonly ?UploadedFile $foto = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            nome: $validated['nome'] ?? null,
            email: $validated['email'] ?? null,
            password: $validated['password'] ?? null,
            telefone: $validated['telefone'] ?? null,
            foto: $validated['foto'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'nome'     => $this->nome,
            'email'    => $this->email,
            'password' => $this->password,
            'telefone' => $this->telefone,
            'foto'     => $this->foto,
        ], fn($v) => $v !== null);
    }
}