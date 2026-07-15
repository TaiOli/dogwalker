<?php

namespace App\Services;

use App\DTOs\Tour\CreateTourDTO;
use App\DTOs\Tour\TourResponseDTO;
use App\Models\Evaluation;
use App\Models\Tour;
use App\Repositories\Interfaces\TourRepositoryInterface;
use Illuminate\Support\Collection;
use App\Exceptions\TourNotFoundException;
use App\Exceptions\TourUnauthorizedException;
use App\Exceptions\TourInvalidStatusException;
use App\Repositories\Services\Contracts\TourServiceInterface;

class TourService implements TourServiceInterface
{
    public function __construct(
        private TourRepositoryInterface $tourRepository
    ) {}


    public function create(CreateTourDTO $dto): Evaluation
    {
        return $this->tourRepository->create($dto->toArray());
    }

    public function listAvailable(?int $walkerId = null): Collection
    {
        return $this->tourRepository->listAvailable($walkerId);
    }

    public function accept(int $id, int $walkerId): Tour
    {
        $tour = $this->findOrFail($id);

        if ($tour->status !== 'pendente') {
            throw new TourInvalidStatusException('Este passeio já foi respondido');
        }

        // se o passeio foi direcionado a um passeador específico, só ele pode aceitar
        if ($tour->passeador_id !== null && $tour->passeador_id !== $walkerId) {
            throw new TourInvalidStatusException('Este passeio foi direcionado a outro passeador');
        }

        return $this->tourRepository->update($tour, [
            'passeador_id' => $walkerId,
            'status' => 'aceito',
        ]);
    }

    public function reject(int $id): Tour
    {
        $tour = $this->findOrFail($id);

        if ($tour->status !== 'pendente') {
            throw new TourInvalidStatusException('Este passeio já foi respondido');
        }

        return $this->tourRepository->update($tour, [
            'status' => 'recusado',
        ]);
    }

    public function cancel(int $id, int $userId): Tour
    {
        $tour = $this->findOrFail($id);

        if ($tour->tutor_id !== $userId) {
            throw new TourUnauthorizedException('Apenas o tutor que solicitou pode cancelar este passeio');
        }

        return $this->tourRepository->update($tour, [
            'status' => 'cancelado',
        ]);
    }

    public function complete(int $id, int $userId): Tour
    {
        $tour = $this->findOrFail($id);

        if ($tour->passeador_id !== $userId) {
            throw new TourUnauthorizedException('Apenas o passeador responsável pode finalizar este passeio');
        }

        if ($tour->status !== 'aceito') {
            throw new TourInvalidStatusException('Este passeio não está em andamento');
        }

        return $this->tourRepository->update($tour, [
            'status' => 'finalizado',
        ]);
    }

    public function myTours($user): array
    {
        if ($user->tipo_usuario === 'tutor') {
            $tours = $this->tourRepository->findByTutor($user->id);

            return $tours->map(function (Tour $tour) {
                $review = Evaluation::where('passeio_id', $tour->id)
                    ->where('tipo_avaliador', 'tutor')
                    ->first();

                $tour->review_by_tutor = $review ? [
                    'rating' => $review->nota, 
                    'comment' => $review->comentario,
                ]: null;
                $tour->rated_by_tutor = (bool) $review;

                return (new TourResponseDTO($tour))->toArray();
            })->values()->all();
        }

        if ($user->tipo_usuario === 'passeador') {
            $tours = $this->tourRepository->findByWalker($user->id);

            return $tours->map(function (Tour $tour) {
                $review = Evaluation::where('passeio_id', $tour->id)
                    ->where('tipo_avaliador', 'passeador')
                    ->first();

                $tour->review_by_walker = $review ? [
                    'rating' => $review->nota, 
                    'comment' => $review->comentario
                ]: null;
                $tour->rated_by_walker = (bool) $review;

                return (new TourResponseDTO($tour))->toArray();
            })->values()->all();
        }

        return [];
    }

    public function delete(int $id, int $userId): void
    {
        $tour = $this->findOrFail($id);

        if ($tour->tutor_id !== $userId) {
            throw new TourUnauthorizedException('Apenas o tutor que solicitou pode remover este passeio');
        }

        $this->tourRepository->delete($tour);
    }

    private function findOrFail(int $id): Tour
    {
        $tour = $this->tourRepository->find($id);

        if (!$tour) {
            throw new TourNotFoundException();
        }

        return $tour;
    }
}