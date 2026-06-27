<?php

namespace App\Services;

use App\Models\Passeio;

class PasseioService
{
    public function create(array $data, int $tutorId)
    {
        return Passeio::create([
            ...$data,
            'tutor_id' => $tutorId,
            'status' => 'pendente',
        ]);
    }

    public function listarDisponiveis()
    {
        return Passeio::with(['dog', 'tutor'])
            ->whereIn('status', ['pendente', 'aceito'])
            ->latest()
            ->get();
    }

    public function aceitar(int $id, int $passeadorId)
    {
        $passeio = Passeio::findOrFail($id);

        $passeio->update([
            'passeador_id' => $passeadorId,
            'status' => 'aceito'
        ]);

        return $passeio;
    }

    public function recusar(int $id)
    {
        $passeio = Passeio::findOrFail($id);

        $passeio->update([
            'status' => 'recusado'
        ]);

        return $passeio->fresh();
    }

    public function meusPasseios($user)
    {
        if ($user->tipo_usuario === 'tutor') {
            return Passeio::with('dog')
                ->where('tutor_id', $user->id)
                ->whereIn('status', ['pendente', 'aceito'])
                ->latest()
                ->get();
        }

        if ($user->tipo_usuario === 'passeador') {
            return Passeio::with('dog')
                ->whereIn('status', ['pendente', 'aceito'])
                ->latest()
                ->get();
        }

        return collect();
    }

    public function excluir(int $id)
    {
        $passeio = Passeio::findOrFail($id);

        $passeio->delete();
    }
}