<?php

namespace  App\Repositories\Eloquent;

use App\Models\Evaluation;
use App\Repositories\Contracts\EvaluationRepositoryInterface;

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