<?php

namespace App\Repositories\Services\Contracts;

use App\DTOs\Dog\CreateDogDTO;
use App\DTOs\Dog\UpdateDogDTO;
use App\Models\Dog;
use Illuminate\Database\Eloquent\Collection;

interface DogServiceInterface
{
    public function create(CreateDogDTO $dto): Dog;
    public function myDogs(array $data): Collection;
    public function update(UpdateDogDTO $dto, int $dogId, int $userId): Dog;
    public function delete(int $dogId, int $userId): void;
}