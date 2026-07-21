<?php

namespace App\DTOs\Tour;

use App\Models\Tour;

class TourResponseDTO
{
    public readonly int     $id;
    public readonly string  $status;
    public readonly string  $data;
    public readonly string  $hora;
    public readonly int     $duracao;
    public readonly string  $local;
    public readonly float   $valor;
    public readonly ?array  $dog;
    public readonly ?array  $tutor;
    public readonly ?array  $walker;
    public readonly ?array  $reviewByTutor;
    public readonly ?array  $reviewByWalker;
    public readonly bool    $ratedByTutor;
    public readonly bool    $ratedByWalker;

    public function __construct(Tour $tour)
    {
        $this->id      = (int) $tour->id;
        $this->status  = $tour->status;
        $this->data    = $tour->data;
        $this->hora    = $tour->hora;
        $this->duracao = (int) $tour->duracao;
        $this->local   = $tour->local;
        $this->valor   = (float) $tour->valor;

        $this->dog = $tour->dog ? [
            'id'   => (int) $tour->dog->id,
            'nome' => $tour->dog->nome,
        ] : null;

        $this->tutor = $tour->tutor ? [
            'id'   => (int) $tour->tutor->id,
            'nome' => $tour->tutor->nome,
        ] : null;

        $this->walker = $tour->walker ? [
            'id'   => (int) $tour->walker->id,
            'nome' => $tour->walker->nome,
        ] : null;

        $this->reviewByTutor   = $tour->review_by_tutor ?? null;
        $this->reviewByWalker  = $tour->review_by_walker ?? null;
        $this->ratedByTutor    = (bool) ($tour->rated_by_tutor ?? false);
        $this->ratedByWalker   = (bool) ($tour->rated_by_walker ?? false);
    }

    public function toArray(): array
    {
        return [
            'id'               => $this->id,
            'status'           => $this->status,
            'data'             => $this->data,
            'hora'             => $this->hora,
            'duracao'          => $this->duracao,
            'local'            => $this->local,
            'valor'            => $this->valor,
            'dog'              => $this->dog,
            'tutor'            => $this->tutor,
            'walker'           => $this->walker,
            'review_by_tutor'  => $this->reviewByTutor,
            'review_by_walker' => $this->reviewByWalker,
            'rated_by_tutor'   => $this->ratedByTutor,
            'rated_by_walker'  => $this->ratedByWalker,
        ];
    }

    public static function collection(iterable $tours): array
    {
        return collect($tours)
            ->map(fn(Tour $t) => (new self($t))->toArray())
            ->values()
            ->all();
    }
}