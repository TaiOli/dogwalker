<?php

namespace App\Services;

use App\Models\Evaluation;
use App\Enums\TourStatus;
use App\DTOs\Evaluation\CreateEvaluationDTO;
use App\Repositories\Contracts\EvaluationRepositoryInterface;
use App\Repositories\Contracts\TourRepositoryInterface;
use App\Repositories\Services\Contracts\EvaluationServiceInterface;
use App\Exceptions\TourNotFoundException;
use App\Exceptions\EvaluationTourNotFinishedException;
use App\Exceptions\EvaluationAlreadyExistsException;
use App\Notifications\EvaluationReceivedNotification;

class EvaluationService implements EvaluationServiceInterface
{
    public function __construct(
        private EvaluationRepositoryInterface $evaluationRepository,
        private TourRepositoryInterface       $tourRepository
    ) {}

    public function create(CreateEvaluationDTO $dto): Evaluation
    {
        $tour = $this->tourRepository->find($dto->passeioId);

        if (!$tour) {
            throw new TourNotFoundException();
        }

        if ($tour->status !== TourStatus::FINALIZADO) {
            throw new EvaluationTourNotFinishedException();
        }

        $alreadyEvaluated = $this->evaluationRepository->existsForTourAndType(
            $tour->id,
            $dto->tipoAvaliador
        );

        if ($alreadyEvaluated) {
            throw new EvaluationAlreadyExistsException();
        }

        $evaluation = $this->evaluationRepository->create([
            'passeio_id'    => $tour->id,
            'tutor_id'      => $tour->tutor_id,
            'passeador_id'  => $tour->passeador_id,
            'nota'          => $dto->nota,
            'comentario'    => $dto->comentario,
            'tipo_avaliador' => $dto->tipoAvaliador,
        ]);

        // Se quem avaliou foi o tutor, quem recebe a notificação é o passeador ( e vice-versa )
        $recipient = $dto->tipoAvaliador === 'tutor' ? $tour->walker : $tour->tutor;

        if ($recipient) {
            $recipient->notify(new EvaluationReceivedNotification($evaluation));
        }

        return $evaluation;
    }
}