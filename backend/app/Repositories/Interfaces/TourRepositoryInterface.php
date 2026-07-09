<?php
namespace App\Repositories\Interfaces;
use App\Models\Tour;
use Illuminate\Database\Eloquent\Collection;
interface TourRepositoryInterface
{
    public function create(array $data): Tour;
    public function find(int $id): Tour;
    public function update(Tour $tour, array $data): Tour;
    public function delete(Tour $tour): void;
    public function listAvailable(?int $walkerId = null): Collection;
    public function findByTutor(int $tutorId): Collection;
    public function findByWalker(int $walkerId): Collection;
}