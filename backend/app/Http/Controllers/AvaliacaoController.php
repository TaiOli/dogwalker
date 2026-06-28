<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use App\Services\AvaliacaoService;
use Illuminate\Http\Request;

class AvaliacaoController extends Controller
{
    public function __construct(
        private AvaliacaoService $avaliacaoService
    ) {}

    public function store(Request $request)
    {
        $data = $request->validate([
            'passeio_id' => 'required|exists:passeios,id',
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string',
            'tipo_avaliador' => 'required|in:tutor,passeador',
        ]);

        $passeio = \App\Models\Passeio::findOrFail($data['passeio_id']);

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
        ]);
    }
}