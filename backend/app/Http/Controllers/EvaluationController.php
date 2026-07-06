<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEvaluationRequest;
use App\Models\Tour;
use App\Services\EvaluationService;

class EvaluationController extends Controller
{
    public function __construct(
        private EvaluationService $evaluationService
    ) {}

    public function store(StoreEvaluationRequest $request)
    {
        $data = $request->validated();

        $tour = Tour::findOrFail($data['passeio_id']);

        $evaluation = $this->evaluationService->create([
            'passeio_id' => $tour->id,
            'tutor_id' => $tour->tutor_id,
            'passeador_id' => $tour->passeador_id,
            'nota' => $data['nota'],
            'comentario' => $data['comentario'] ?? null,
            'tipo_avaliador' => $data['tipo_avaliador'],
        ]);

        return response()->json([
            'message' => 'Avaliação enviada com sucesso',
            'avaliacao' => $evaluation
        ], 201);
    }
}