<?php

namespace App\Repositories\Interfaces;

use App\Models\Dog;
use Illuminate\Database\Eloquent\Collection;

interface DogRepositoryInterface
{
    public function create(array $data): Dog;
    public function findByUserId(int $userId, ?string $search = null): Collection;
    public function findById(int $id): ?Dog;
    public function update(int $id, array $data): Dog;
    public function delete(int $id): void;
}