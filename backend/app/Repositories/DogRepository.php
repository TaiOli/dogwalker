<?php

namespace App\Repositories;

use App\Models\Dog;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\DogRepositoryInterface;

class DogRepository implements DogRepositoryInterface
{
    public function create(array $data): Dog
    {
        return Dog::create($data);
    }

    public function findByUserId(int $userId): Collection
    {
        return Dog::where('user_id', $userId)->get();
    }
}