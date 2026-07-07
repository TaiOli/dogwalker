<?php

namespace App\Repositories\Interfaces;

use App\Models\Evaluation;
use Illuminate\Database\Eloquent\Collection;

interface EvaluationRepositoryInterface
{
    public function create(array $data): Evaluation;
}