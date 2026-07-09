<?php

namespace App\Repositories\Interfaces;

use App\Models\Evaluation;

interface EvaluationRepositoryInterface
{
    public function create(array $data): Evaluation;
    public function existsForTourAndType(int $tourId, string $tipoAvaliador): bool;
}