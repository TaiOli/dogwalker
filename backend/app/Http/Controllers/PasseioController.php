<?php

namespace App\Http\Controllers;

use App\Models\Passeio;
use Illuminate\Http\Request;

class PasseioController extends Controller
{
    public function store(Request $request)
    {
        $passeio = Passeio::create([
            'dog_id' => $request->dog_id,
            'tutor_id' => $request->tutor_id,
            'data' => $request->data,
            'hora' => $request->hora,
            'duracao' => $request->duracao,
            'local' => $request->local,
            'valor' => $request->valor,
            'status' => 'pendente',
        ]);

        return response()->json([
            'message' => 'Passeio solicitado com sucesso',
            'walk' => $passeio
        ]);
    }
}
