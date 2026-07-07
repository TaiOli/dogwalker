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
}