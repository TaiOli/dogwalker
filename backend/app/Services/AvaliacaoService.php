<?php

namespace App\Services;

use App\Models\Avaliacao;

class AvaliacaoService
{
    public function create(array $data)
    {
        return Avaliacao::create($data);
    }
}