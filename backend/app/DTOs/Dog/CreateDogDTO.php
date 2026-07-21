<?php

namespace App\DTOs\Dog;

use Illuminate\Http\UploadedFile;

class CreateDogDTO
{
    public function __construct(
        public readonly int           $userId,
        public readonly string        $nome,
        public readonly int           $idade,
        public readonly string        $porte,
        public readonly string        $raca,
        public readonly ?string       $observacoes = null,
        public readonly ?UploadedFile $foto = null,
    ) {}

    public static function fromRequest(array $validated, int $userId): self
    {
        return new self(
            userId: $userId,
            nome: $validated['nome'],
            idade: $validated['idade'],
            porte: $validated['porte'],
            raca: $validated['raca'],
            observacoes: $validated['observacoes'] ?? null,
            foto: $validated['foto'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'user_id'     => $this->userId,
            'nome'        => $this->nome,
            'idade'       => $this->idade,
            'porte'       => $this->porte,
            'raca'        => $this->raca,
            'observacoes' => $this->observacoes,
            'foto'        => $this->foto,
        ], fn($v) => $v !== null);
    }
}