<?php

namespace App\Repositories\Interfaces;

use App\Models\Dog;
use Illuminate\Database\Eloquent\Collection;

interface DogRepositoryInterface
{
    public function create(array $data): Dog;
    public function findByUserId(int $userId): Collection;
}