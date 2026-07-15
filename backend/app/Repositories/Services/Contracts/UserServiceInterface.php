<?php

namespace App\Repositories\Services\Contracts;

use App\DTOs\User\CreateUserDTO;
use App\DTOs\User\UpdateUserDTO;
use App\Models\User;

interface UserServiceInterface{

    public function create(CreateUserDTO $dto): User;
    public function login(array $data): ?array;
    public function walkers(): array;
    public function show(int $id): array;
    public function showTutor(int $id): array;
    public function update(int $id, UpdateUserDTO $dto, int $authUserId): User;
}