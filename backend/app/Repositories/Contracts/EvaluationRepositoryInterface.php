<?php

namespace App\Repositories\Contracts;

use App\Models\Evaluation;

interface EvaluationRepositoryInterface
{
    public function create(array $data): Evaluation;
    public function existsForTourAndType(int $tourId, string $tipoAvaliador): bool;
}