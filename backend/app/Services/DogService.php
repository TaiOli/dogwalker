<?php

namespace App\Services;

use App\Models\Dog;
use App\Repositories\Interfaces\DogRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

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

    public function myDogs(array $data): Collection
    {
        return $this->dogRepository->findByUserId(
            Auth::id(),
            $data['search'] ?? null
        );
    }
}