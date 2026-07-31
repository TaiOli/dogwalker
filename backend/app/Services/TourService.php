<?php

namespace App\Services;

use App\Enums\TourStatus;
use App\Enums\TipoUsuario;
use App\Models\Evaluation;
use App\Models\Tour;
use App\DTOs\Tour\CreateTourDTO;
use App\DTOs\Tour\TourResponseDTO;
use App\Repositories\Contracts\TourRepositoryInterface;
use App\Repositories\Services\Contracts\TourServiceInterface;
use App\Exceptions\TourNotFoundException;
use App\Exceptions\TourInvalidStatusException;
use App\Notifications\TourAcceptedNotification;
use App\Notifications\TourCompletedNotification;
use App\Notifications\TourCancelledNotification;
use Illuminate\Database\Eloquent\Collection;

class TourService implements TourServiceInterface
{
    public function __construct(
        private TourRepositoryInterface $tourRepository
    ) {}

    public function create(CreateTourDTO $dto): Tour
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

        if ($tour->status !== TourStatus::PENDENTE) {
            throw new TourInvalidStatusException('Este passeio já foi respondido!');
        }

        if ($tour->passeador_id !== null && $tour->passeador_id !== $walkerId) {
            throw new TourInvalidStatusException('Este passeio foi direcionado a outro passeador!');
        }

        $tour = $this->tourRepository->update($tour, [
            'passeador_id' => $walkerId,
            'status' => TourStatus::ACEITO->value,
        ]);

        $tour->tutor->notify(new TourAcceptedNotification($tour));

        return $tour;
    }

    public function reject(int $id): Tour
    {
        $tour = $this->findOrFail($id);

        if ($tour->status !== TourStatus::PENDENTE) {
            throw new TourInvalidStatusException('Este passeio já foi respondido!');
        }

        return $this->tourRepository->update($tour, [
            'status' => TourStatus::RECUSADO->value,
        ]);
    }

    public function cancel(int $id): Tour
    {
        $tour = $this->findOrFail($id);

        $tour = $this->tourRepository->update($tour, [
            'status' => TourStatus::CANCELADO->value,
        ]);

        if ($tour->walker) {
            $tour->walker->notify(new TourCancelledNotification($tour));
        }

        return $tour;
    }

    public function complete(int $id): Tour
    {
        $tour = $this->findOrFail($id);

        if ($tour->status !== TourStatus::ACEITO) {
            throw new TourInvalidStatusException('Este passeio não está em andamento!');
        }

        return $this->tourRepository->update($tour, [
            'status' => TourStatus::FINALIZADO->value,
        ]);

        $tour->tutor->notify(new TourCompletedNotification($tour));

        return $tour;
    }

    public function myTours($user): array
    {
        if ($user->tipo_usuario === TipoUsuario::TUTOR) {
            $tours = $this->tourRepository->findByTutor($user->id);

            return $tours->map(function (Tour $tour) {
                $review = Evaluation::where('passeio_id', $tour->id)
                    ->where('tipo_avaliador', 'tutor')
                    ->first();

                $tour->review_by_tutor = $review ? [
                    'rating' => $review->nota,
                    'comment' => $review->comentario,
                ] : null;
                $tour->rated_by_tutor = (bool) $review;

                return (new TourResponseDTO($tour))->toArray();
            })->values()->all();
        }

        if ($user->tipo_usuario === TipoUsuario::PASSEADOR) {
            $tours = $this->tourRepository->findByWalker($user->id);

            return $tours->map(function (Tour $tour) {
                $review = Evaluation::where('passeio_id', $tour->id)
                    ->where('tipo_avaliador', 'passeador')
                    ->first();

                $tour->review_by_walker = $review ? [
                    'rating' => $review->nota,
                    'comment' => $review->comentario
                ] : null;
                $tour->rated_by_walker = (bool) $review;

                return (new TourResponseDTO($tour))->toArray();
            })->values()->all();
        }

        return [];
    }

    public function delete(int $id): void
    {
        $tour = $this->findOrFail($id);
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