<?php

namespace App\Services;

use App\Models\Evaluation;

class AvaliacaoService
{
    public function create(array $data)
    {
        return Evaluation::create($data);
    }
}