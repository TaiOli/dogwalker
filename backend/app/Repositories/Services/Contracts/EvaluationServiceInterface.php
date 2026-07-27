<?php

namespace App\Repositories\Services\Contracts;

use App\Models\Evaluation;
use App\DTOs\Evaluation\CreateEvaluationDTO;

interface EvaluationServiceInterface
{
    public function create(CreateEvaluationDTO $dto): Evaluation;
}