<?php

namespace App\Services;

use App\Models\Evaluation;

class EvaluationService
{
    public function create(array $data)
    {
        return Evaluation::create($data);
    }
}