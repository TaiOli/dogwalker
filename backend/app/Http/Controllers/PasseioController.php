<?php

namespace App\Http\Controllers;

use App\Models\Passeio;
use App\Http\Requests\StorePasseioRequest;
use Illuminate\Http\Request;

class PasseioController extends Controller
{
    // Criar Passeio
    public function store(StorePasseioRequest $request)
    {
        $passeio = Passeio::create([
            ...$request->validated(),
            'tutor_id' => $request->user()->id,
            'status' => 'pendente',
        ]);

        return response()->json([
            'message' => 'Passeio solicitado com sucesso',
            'passeio' => $passeio
        ]);
    }

    // Listar Passeio
    public function index()
    {
        return Passeio::with(['dog', 'tutor'])
            ->whereNull('passeador_id')
            ->where('status', 'pendente')
            ->get();
    }

    public function aceitar($id, Request $request)
    {
        $passeio = Passeio::findOrFail($id);

        $passeio->update([
            'passeador_id' => $request->user()->id,
            'status' => 'aceito'
        ]);

        return response()->json([
            'message' => 'Passeio aceito com sucesso',
            'passeio' => $passeio
        ]);
    }

    public function recusar($id)
    {
        $passeio = Passeio::findOrFail($id);

        $passeio->update([
            'status' => 'recusado'
        ]);

        return response()->json([
            'message' => 'Passeio recusado'
        ]);
    }
}