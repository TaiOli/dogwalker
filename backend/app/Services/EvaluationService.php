<?php

namespace App\Services;

use App\Models\Evaluation;
use App\Repositories\Interfaces\EvaluationRepositoryInterface;

class EvaluationService
{
    public function __construct(
        private EvaluationRepositoryInterface $evaluationRepository
    ) {}

    public function create(array $data):Evaluation
    {
        return $this->evaluationRepository->create($data);
    }
}