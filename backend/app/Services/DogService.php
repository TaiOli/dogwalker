<?php

namespace App\Services;

use App\Models\Dog;
use App\Repositories\Interfaces\DogRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\DogNotFoundException;
use App\Exceptions\DogUnauthorizedException;

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

    public function update(array $data, int $dogId, int $userId): Dog
    {
        $dog = $this->dogRepository->findById($dogId);

        if (!$dog) {
            throw new DogNotFoundException();
        }

        if ($dog->user_id !== $userId) {
            throw new DogUnauthorizedException();
        }

        return $this->dogRepository->update($dogId, $data);
    }
}