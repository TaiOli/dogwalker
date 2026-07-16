<?php

namespace App\Services;

use App\DTOs\Dog\CreateDogDTO;
use App\DTOs\Dog\UpdateDogDTO;
use App\Models\Dog;
use App\Repositories\Contracts\DogRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\DogNotFoundException;
use App\Exceptions\DogUnauthorizedException;
use App\Repositories\Services\Contracts\DogServiceInterface;

class DogService implements DogServiceInterface
{
    public function __construct(
        private DogRepositoryInterface $dogRepository
    ) {}

    public function create(CreateDogDTO $dto): Dog
    {
        $data = $dto->toArray();

        return $this->dogRepository->create($data);
    }

    public function myDogs(array $data): Collection
    {
        return $this->dogRepository->findByUserId(
            Auth::id(),
            $data['search'] ?? null
        );
    }

    public function update(UpdateDogDTO $dto, int $dogId, int $userId): Dog
    {
        $dog = $this->dogRepository->findById($dogId);

        if (!$dog) {
            throw new DogNotFoundException();
        }

        if ($dog->user_id !== $userId) {
            throw new DogUnauthorizedException();
        }

        $data = $dto->toArray();

        return $this->dogRepository->update($dogId, $data);
    }

    public function delete(int $dogId, int $userId): void
    {
        $dog = $this->dogRepository->findById($dogId);

        if (!$dog) {
            throw new DogNotFoundException();
        }

        if ($dog->user_id !== $userId) {
            throw new DogUnauthorizedException();
        }

        $this->dogRepository->delete($dogId);
    }
}