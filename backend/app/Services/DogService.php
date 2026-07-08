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

    public function update(array $data, int $dogId, int $userId): Dog
    {
        $dog = $this->dogRepository->findById($dogId);

        if (!$dog || $dog->user_id !== $userId) {
            throw new \Exception('Cachorro não encontrado ou você não tem permissão para atualizá-lo.');
        }

        return $this->dogRepository->update($dogId, $data);
    }
}