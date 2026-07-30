<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Http\Requests\StoreEvaluationRequest;
use App\Repositories\Services\Contracts\EvaluationServiceInterface;

class EvaluationController extends Controller
{
    public function __construct(
        private EvaluationServiceInterface $evaluationService
    ) {}

    public function store(StoreEvaluationRequest $request)
    {
        $dto  = $request->toDto();
        $tour = Tour::findOrFail($dto->passeioId);

        $this->authorize('evaluate', [$tour, $dto->tipoAvaliador]);

        $evaluation = $this->evaluationService->create($dto);

        return response()->json([
            'message' => 'Avaliação enviada com sucesso!',
            'avaliacao' => $evaluation
        ], 201);
    }
}