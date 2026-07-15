<?php

namespace App\Repositories\Services\Contracts;

use App\DTOs\Dog\CreateDogDTO;
use App\DTOs\Dog\UpdateDogDTO;
use App\Models\Dog;
use Illuminate\Database\Eloquent\Collection;

interface DogServiceInterface{

    public function create(CreateDogDTO $dto): Dog;
    public function getDogsByUserId(int $userId, ?string $search = null): Collection;
    public function getDogById(int $id): Dog;
    public function update(UpdateDogDTO $dto, int $dogId, int $userId): Dog;
    public function delete(int $id): void;
    public function myDogs(array $data): Collection;

}