<?php

namespace App\Repositories\Services\Contracts;

use App\DTOs\Tour\CreateTourDTO;
use App\Models\Tour;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Evaluation;

interface TourServiceInterface{

    public function create(CreateTourDTO $dto): Evaluation;
    public function find(int $id): Tour;
    public function update(Tour $tour, array $data): Tour;
    public function delete(Tour $tour): void;
    public function listAvailable(?int $walkerId = null): Collection;
    public function findByTutor(int $tutorId): Collection;
    public function findByWalker(int $walkerId): Collection;
}