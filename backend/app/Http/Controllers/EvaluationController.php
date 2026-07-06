<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvaluationRequest;
use App\Models\Tour;
use App\Services\EvaluationService;

class EvaluationController extends Controller
{
    public function __construct(
        private EvaluationService $avaliacaoService
    ) {}

    public function store(EvaluationRequest $request)
    {
        $data = $request->validated();

        $passeio = Tour::findOrFail($data['passeio_id']);

        $avaliacao = $this->avaliacaoService->create([
            'passeio_id' => $passeio->id,
            'tutor_id' => $passeio->tutor_id,
            'passeador_id' => $passeio->passeador_id,
            'nota' => $data['nota'],
            'comentario' => $data['comentario'] ?? null,
            'tipo_avaliador' => $data['tipo_avaliador'],
        ]);

        return response()->json([
            'message' => 'Avaliação enviada com sucesso',
            'avaliacao' => $avaliacao
        ], 201);
    }
}