<?php

namespace App\Repositories\Services\Contracts;

use App\DTOs\Tour\CreateTourDTO;
use App\Models\Tour;
use Illuminate\Database\Eloquent\Collection;

interface TourServiceInterface
{

    public function create(CreateTourDTO $dto): Tour;
    public function listAvailable(?int $walkerId = null): Collection;
    public function accept(int $id, int $walkerId): Tour;
    public function reject(int $id): Tour;
    public function cancel(int $id, int $userId): Tour;
    public function complete(int $id, int $userId): Tour;
    public function myTours($user): array;
    public function delete(int $id, int $userId): void;
}