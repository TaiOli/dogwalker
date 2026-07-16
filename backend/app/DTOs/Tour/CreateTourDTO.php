<?php

namespace App\DTOs\Tour;

class CreateTourDTO
{
    public function __construct(
        public readonly int    $tutorId,
        public readonly int    $dogId,
        public readonly string $data,
        public readonly string $hora,
        public readonly int    $duracao,
        public readonly string $local,
        public readonly float  $valor,
    ) {}


    public static function fromRequest(array $validated, int $tutorId): self
    {
        return new self(
            tutorId: $tutorId,
            dogId: (int) $validated['dog_id'],
            data: $validated['data'],
            hora: $validated['hora'],
            duracao: (int) $validated['duracao'],
            local: $validated['local'],
            valor: (float) $validated['valor'],
        );
    }

    public function toArray(): array
    {
        return [
            'tutor_id' => $this->tutorId,
            'dog_id'   => $this->dogId,
            'data'     => $this->data,
            'hora'     => $this->hora,
            'duracao'  => $this->duracao,
            'local'    => $this->local,
            'valor'    => $this->valor,
            'status'   => 'pendente',
        ];
    }
}