<?php

namespace App\DTOs\Evaluation;

use App\Models\Evaluation;

class EvaluationResponseDTO
{
    public readonly int     $id;
    public readonly int     $passeioId;
    public readonly int     $nota;
    public readonly ?string $comentario;
    public readonly string  $tipoAvaliador;
    public readonly string  $createdAt;
    public readonly ?array  $tutor;
    public readonly ?array  $walker;

    public function __construct(Evaluation $evaluation)
    {
        $this->id            = $evaluation->id;
        $this->passeioId     = $evaluation->passeio_id;
        $this->nota          = $evaluation->nota;
        $this->comentario    = $evaluation->comentario;
        $this->tipoAvaliador = $evaluation->tipo_avaliador;
        $this->createdAt     = $evaluation->created_at->toISOString();

        $this->tutor = $evaluation->tutor ? [
            'id'   => $evaluation->tutor->id,
            'nome' => $evaluation->tutor->nome,
        ] : null;

        $this->walker = $evaluation->walker ? [
            'id'   => $evaluation->walker->id,
            'nome' => $evaluation->walker->nome,
        ] : null;
    }

    public function toArray(): array
    {
        return [
            'id'             => $this->id,
            'passeio_id'     => $this->passeioId,
            'nota'           => $this->nota,
            'comentario'     => $this->comentario,
            'tipo_avaliador' => $this->tipoAvaliador,
            'created_at'     => $this->createdAt,
            'tutor'          => $this->tutor,
            'walker'         => $this->walker,
        ];
    }

    public static function collection(iterable $evaluations): array
    {
        return collect($evaluations)
            ->map(fn(Evaluation $e) => (new self($e))->toArray())
            ->values()
            ->all();
    }
}