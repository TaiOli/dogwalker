<?php
namespace App\Services;

use App\Models\Evaluation;
use App\Repositories\Interfaces\EvaluationRepositoryInterface;
use App\Repositories\Interfaces\TourRepositoryInterface;
use App\Exceptions\TourNotFoundException;
use App\Exceptions\EvaluationTourNotFinishedException;
use App\Exceptions\EvaluationAlreadyExistsException;

class EvaluationService
{
    public function __construct(
        private EvaluationRepositoryInterface $evaluationRepository,
        private TourRepositoryInterface $tourRepository
    ) {}

    public function create(array $data): Evaluation
    {
        $tour = $this->tourRepository->find($data['passeio_id']);

        if (!$tour) {
            throw new TourNotFoundException();
        }

        if ($tour->status !== 'finalizado') {
            throw new EvaluationTourNotFinishedException();
        }

        $alreadyEvaluated = $this->evaluationRepository->existsForTourAndType(
            $tour->id,
            $data['tipo_avaliador']
        );

        if ($alreadyEvaluated) {
            throw new EvaluationAlreadyExistsException();
        }

        return $this->evaluationRepository->create([
            'passeio_id' => $tour->id,
            'tutor_id' => $tour->tutor_id,
            'passeador_id' => $tour->passeador_id,
            'nota' => $data['nota'],
            'comentario' => $data['comentario'] ?? null,
            'tipo_avaliador' => $data['tipo_avaliador'],
        ]);
    }
}