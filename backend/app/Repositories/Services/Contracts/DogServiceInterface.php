<?php

namespace App\Repositories\Services\Contracts;

use App\Models\Dog;
use App\DTOs\Dog\CreateDogDTO;
use App\DTOs\Dog\UpdateDogDTO;
use Illuminate\Database\Eloquent\Collection;

interface DogServiceInterface
{
    public function create(CreateDogDTO $dto): Dog;
    public function myDogs(array $data): Collection;
    public function update(UpdateDogDTO $dto, int $dogId): Dog;
    public function delete(int $dogId): void;
}