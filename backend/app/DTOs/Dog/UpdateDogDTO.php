<?php

namespace App\DTOs\Dog;

class UpdateDogDTO
{
    public function __construct(
        public readonly ?string       $nome = null,
        public readonly ?int          $idade = null,
        public readonly ?string       $porte = null,
        public readonly ?string       $raca = null,
        public readonly ?string       $observacoes = null,
        public readonly ?string $foto = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            nome: $validated['nome'] ?? null,
            idade: isset($validated['idade']) ? (int) $validated['idade'] : null,
            porte: $validated['porte'] ?? null,
            raca: $validated['raca'] ?? null,
            observacoes: $validated['observacoes'] ?? null,
            foto: $validated['foto'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'nome'        => $this->nome,
            'idade'       => $this->idade,
            'porte'       => $this->porte,
            'raca'        => $this->raca,
            'observacoes' => $this->observacoes,
            'foto'        => $this->foto,
        ], fn($v) => $v !== null);
    }
}