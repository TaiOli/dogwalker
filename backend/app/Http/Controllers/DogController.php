<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use Illuminate\Http\Request;

class DogController extends Controller
{
    public function store(Request $request)
    {
        $dog = Dog::create([
            'user_id' => $request->user_id,
            'nome' => $request->nome,
            'idade' => $request->idade,
            'porte' => $request->porte,
            'raca' => $request->raca,
            'foto' => $request->foto,
            'observacoes' => $request->observacoes,
        ]);

        return response()->json([
            'message' => 'Cachorro cadastrado com sucesso',
            'dog' => $dog
        ]);
    }
}
