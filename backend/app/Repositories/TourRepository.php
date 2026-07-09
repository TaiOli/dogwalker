<?php
namespace App\Repositories;
use App\Models\Tour;
use App\Repositories\Interfaces\TourRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
class TourRepository implements TourRepositoryInterface
{
    public function create(array $data): Tour
    {
        return Tour::create($data);
    }
    public function find(int $id): Tour
    {
        return Tour::findOrFail($id);
    }
    public function update(Tour $tour, array $data): Tour
    {
        $tour->update($data);
        return $tour->fresh();
    }
    public function delete(Tour $tour): void
    {
        $tour->delete();
    }
    public function listAvailable(?int $walkerId = null): Collection
    {
        return Tour::with(['dog', 'tutor'])
            ->whereIn('status', ['pendente', 'aceito'])
            ->where(function ($query) use ($walkerId) {
                // passeios sem passeador definido (abertos a qualquer um)
                $query->whereNull('passeador_id');

                // OU passeios direcionados especificamente a este passeador
                if ($walkerId) {
                    $query->orWhere('passeador_id', $walkerId);
                }
            })
            ->latest()
            ->get();
    }
    public function findByTutor(int $tutorId): Collection
    {
        return Tour::where('tutor_id', $tutorId)
            ->with(['dog', 'passeador'])
            ->get();
    }
    public function findByWalker(int $walkerId): Collection
    {
        return Tour::where('passeador_id', $walkerId)
            ->whereIn('status', ['aceito', 'finalizado', 'cancelado'])
            ->with(['dog', 'tutor'])
            ->get();
    }
}