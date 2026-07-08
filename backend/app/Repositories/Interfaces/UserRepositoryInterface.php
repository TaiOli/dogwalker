<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function create(array $data): User;
    public function findByEmail(string $email): ?User;
    public function findById(int $id): ?User;
    public function getWalkersWithRatingAvg(): Collection;
    public function findWithReceivedEvaluations(int $id): User;
    public function findWithSubmittedEvaluations(int $id): User;
    public function update(int $id, array $data): User;
}