<?php

namespace App\Services;

use App\Models\Evaluation;
use App\Models\Tour;
use App\Repositories\Interfaces\TourRepositoryInterface;
use Illuminate\Support\Collection;

class TourService
{
    public function __construct(
        private TourRepositoryInterface $tourRepository
    ) {}

    public function create(array $data, int $tutorId): Tour
    {
        return $this->tourRepository->create([
            ...$data,
            'tutor_id' => $tutorId,
            'status' => 'pendente',
        ]);
    }

    public function listAvailable(): Collection
    {
        return $this->tourRepository->listAvailable();
    }

    public function accept(int $id, int $walkerId): Tour
    {
        $tour = $this->tourRepository->find($id);

        return $this->tourRepository->update($tour, [
            'passeador_id' => $walkerId,
            'status' => 'aceito',
        ]);
    }

    public function reject(int $id): Tour
    {
        $tour = $this->tourRepository->find($id);

        return $this->tourRepository->update($tour, [
            'status' => 'recusado',
        ]);
    }

    public function cancel(int $id): Tour
    {
        $tour = $this->tourRepository->find($id);

        return $this->tourRepository->update($tour, [
            'status' => 'cancelado',
        ]);
    }

    public function myTours($user): Collection
    {
        if ($user->tipo_usuario === 'tutor') {
            $tours = $this->tourRepository->findByTutor($user->id);

            return $tours->map(function ($tour) {
                $reviewTutor = Evaluation::where('passeio_id', $tour->id)
                    ->where('tipo_avaliador', 'tutor')
                    ->first();

                $tour->review_by_tutor = $reviewTutor;
                $tour->rated_by_tutor = (bool) $reviewTutor;

                return $tour;
            });
        }

        if ($user->tipo_usuario === 'passeador') {
            $tours = $this->tourRepository->findByWalker($user->id);

            return $tours->map(function ($tour) {
                $reviewWalker = Evaluation::where('passeio_id', $tour->id)
                    ->where('tipo_avaliador', 'passeador')
                    ->first();

                $tour->review_by_walker = $reviewWalker;
                $tour->rated_by_walker = (bool) $reviewWalker;

                return $tour;
            });
        }

        return collect();
    }

    public function delete(int $id): void
    {
        $tour = $this->tourRepository->find($id);

        $this->tourRepository->delete($tour);
    }
}