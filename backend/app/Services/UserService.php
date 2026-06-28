<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function create(array $data)
    {
        if (isset($data['foto'])) {
            $data['foto'] = $data['foto']->store('users', 'public');
        }

        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }

    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return null;
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function walkers()
    {
        return User::where('tipo_usuario', 'passeador')
            ->withAvg('avaliacoesRecebidas', 'nota')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'nome' => $user->nome,
                    'telefone' => $user->telefone,
                    'foto' => $user->foto,

                    'media_avaliacao' => round($user->avaliacoesRecebidas_avg_nota ?? 0, 1),
                ];
            });
    }

    public function show($id)
    {
        return User::where('id', $id)
            ->with(['avaliacoesRecebidas' => function ($q) {
                $q->where('tipo_avaliador', 'tutor')
                ->with('tutor')
                ->latest();
            }])
            ->withAvg('avaliacoesRecebidas', 'nota')
            ->firstOrFail();
    }

    public function showTutor($id)
    {
        return User::where('id', $id)
            ->with(['avaliacoesFeitas' => function ($q) {
                $q->where('tipo_avaliador', 'passeador')
                ->with('passeador')
                ->latest();
            }])
            ->firstOrFail();
    }
}