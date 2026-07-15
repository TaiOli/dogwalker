<?php

namespace App\Services;

use App\DTOs\Evaluation\CreateEvaluationDTO;
use App\Models\Evaluation;
use App\Repositories\Interfaces\EvaluationRepositoryInterface;
use App\Repositories\Interfaces\TourRepositoryInterface;
use App\Exceptions\TourNotFoundException;
use App\Exceptions\EvaluationTourNotFinishedException;
use App\Exceptions\EvaluationAlreadyExistsException;
use App\Repositories\Services\Contracts\EvaluationServiceInterface;

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

        if ($tour->status !== 'finalizado') {
            throw new EvaluationTourNotFinishedException();
        }

        $alreadyEvaluated = $this->evaluationRepository->existsForTourAndType(
            $tour->id,
            $dto->tipoAvaliador
        );

        if ($alreadyEvaluated) {
            throw new EvaluationAlreadyExistsException();
        }

        return $this->evaluationRepository->create([
            'passeio_id'    => $tour->id,
            'tutor_id'      => $tour->tutor_id,
            'passeador_id'  => $tour->passeador_id,
            'nota'          => $dto->nota,
            'comentario'    => $dto->comentario,
            'tipo_avaliador' => $dto->tipoAvaliador,
        ]);
    }
}