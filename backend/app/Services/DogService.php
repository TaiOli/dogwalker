<?php

namespace App\Services;

use App\Models\Dog;
use App\DTOs\Dog\CreateDogDTO;
use App\DTOs\Dog\UpdateDogDTO;
use App\Repositories\Contracts\DogRepositoryInterface;
use App\Repositories\Services\Contracts\DogServiceInterface;
use App\Exceptions\DogNotFoundException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;


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

    public function update(UpdateDogDTO $dto, int $dogId): Dog
    {
        $dog = $this->dogRepository->findById($dogId);

        if (!$dog) {
            throw new DogNotFoundException();
        }

        $data = $dto->toArray();

        return $this->dogRepository->update($dogId, $data);
    }

    public function delete(int $dogId): void
    {
        $dog = $this->dogRepository->findById($dogId);

        if (!$dog) {
            throw new DogNotFoundException();
        }

        $this->dogRepository->delete($dogId);
    }
}
