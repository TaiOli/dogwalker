<?php

namespace App\Http\Controllers;

use App\Http\Requests\AvaliacaoRequest;
use App\Models\Passeio;
use App\Services\AvaliacaoService;

class AvaliacaoController extends Controller
{
    public function __construct(
        private AvaliacaoService $avaliacaoService
    ) {}

    public function store(AvaliacaoRequest $request)
    {
        $data = $request->validated();

        $passeio = Passeio::findOrFail($data['passeio_id']);

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