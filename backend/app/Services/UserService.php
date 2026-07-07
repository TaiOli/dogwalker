<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function create(array $data): User
    {
        if (isset($data['foto'])) {
            $data['foto'] = $data['foto']->store('users', 'public');
        }

        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }

    public function login(array $data): ?array
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

    public function walkers(): Collection
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

    public function show($id): User
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

    public function showTutor($id): User
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