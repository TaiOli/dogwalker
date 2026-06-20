<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nome' => $request->nome,
            'telefone' => $request->telefone,
            'tipo_usuario' => $request->tipo_usuario,
            'foto' => $request->foto,
        ]);

        return response()->json([
            'message' => 'Usuário criado com sucesso',
            'user' => $user
        ]);
    }
}
