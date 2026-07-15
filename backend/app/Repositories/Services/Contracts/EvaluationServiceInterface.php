<?php

namespace App\Repositories\Services\Contracts;

use App\DTOs\Evaluation\CreateEvaluationDTO;
use App\Models\Evaluation;

interface EvaluationServiceInterface{

    public function create(CreateEvaluationDTO $dto): Evaluation;
    
}