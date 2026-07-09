<?php

namespace App\Repositories;

use App\Models\Evaluation;
use App\Repositories\Interfaces\EvaluationRepositoryInterface;

class EvaluationRepository implements EvaluationRepositoryInterface
{
    public function create(array $data): Evaluation
    {
        return Evaluation::create($data);
    }

    public function existsForTourAndType(int $tourId, string $tipoAvaliador): bool
    {
        return Evaluation::where('passeio_id', $tourId)
            ->where('tipo_avaliador', $tipoAvaliador)
            ->exists();
    }
}