<?php

namespace App\Services;

use App\Models\Dog;
use App\Repositories\Interfaces\DogRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DogService
{
    public function __construct(
        private DogRepositoryInterface $dogRepository
    ) {}

    public function create(array $data, int $userId): Dog
    {
        return $this->dogRepository->create([
            ...$data,
            'user_id' => $userId
        ]);
    }

    public function myDogs(int $userId): Collection
    {
        return $this->dogRepository->findByUserId($userId);
    }
}