<?php

namespace App\DTOs\Dog;

use App\Models\Dog;

class DogResponseDTO
{
    public readonly int     $id;
    public readonly int     $userId;
    public readonly string  $nome;
    public readonly int     $idade;
    public readonly string  $porte;
    public readonly string  $raca;
    public readonly ?string $observacoes;
    public readonly ?string $foto;

    public function __construct(Dog $dog)
    {
        $this->id          = $dog->id;
        $this->userId      = $dog->user_id;
        $this->nome        = $dog->nome;
        $this->idade       = $dog->idade;
        $this->porte       = $dog->porte;
        $this->raca        = $dog->raca;
        $this->observacoes = $dog->observacoes;
        $this->foto        = $dog->foto;
    }

    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'user_id'     => $this->userId,
            'nome'        => $this->nome,
            'idade'       => $this->idade,
            'porte'       => $this->porte,
            'raca'        => $this->raca,
            'observacoes' => $this->observacoes,
            'foto'        => $this->foto,
        ];
    }

    public static function collection(iterable $dogs): array
    {
        return collect($dogs)
            ->map(fn(Dog $d) => (new self($d))->toArray())
            ->values()
            ->all();
    }
}