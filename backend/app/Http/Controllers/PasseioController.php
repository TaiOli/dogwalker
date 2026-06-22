<?php

namespace App\Http\Controllers;

use App\Models\Passeio;
use App\Http\Requests\StorePasseioRequest;

class PasseioController extends Controller
{
    public function store(StorePasseioRequest $request)
    {
        $passeio = Passeio::create([
            ...$request->validated(),
            'status' => 'pendente',
        ]);

        return response()->json([
            'message' => 'Passeio solicitado com sucesso',
            'passeio' => $passeio
        ]);
    }
}
