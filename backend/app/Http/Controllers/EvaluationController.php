<?php

namespace App\Http\Controllers;

use App\DTOs\Evaluation\CreateEvaluationDTO;
use App\Http\Requests\StoreEvaluationRequest;
use App\Services\EvaluationService;
use App\Exceptions\TourNotFoundException;
use App\Exceptions\EvaluationTourNotFinishedException;
use App\Exceptions\EvaluationAlreadyExistsException;

class EvaluationController extends Controller
{
    public function __construct(
        private EvaluationService $evaluationService
    ) {}

    public function store(StoreEvaluationRequest $request)
    {
        try {

            $dto = CreateEvaluationDTO::fromRequest($request->validated());

            $evaluation = $this->evaluationService->create($dto);

            return response()->json([
                'message' => 'Avaliação enviada com sucesso',
                'avaliacao' => $evaluation
            ], 201);

        } catch (TourNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (EvaluationTourNotFinishedException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        } catch (EvaluationAlreadyExistsException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
    }
}