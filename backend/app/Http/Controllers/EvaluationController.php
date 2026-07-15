<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEvaluationRequest;
use App\Exceptions\TourNotFoundException;
use App\Exceptions\EvaluationTourNotFinishedException;
use App\Exceptions\EvaluationAlreadyExistsException;
use App\Repositories\Services\Contracts\EvaluationServiceInterface;

class EvaluationController extends Controller
{
    public function __construct(
        private EvaluationServiceInterface $evaluationService
    ) {}

    public function store(StoreEvaluationRequest $request)
    {
        try {
            
            $evaluation = $this->evaluationService->create($request->toDto());
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