<?php

namespace App\Services;

use App\Models\Avaliacao;
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

            return Passeio::where('tutor_id', $user->id)
                ->with(['dog', 'passeador'])
                ->get()
                ->map(function ($p) {

                    $avaliacaoTutor = Avaliacao::where('passeio_id', $p->id)
                        ->where('tipo_avaliador', 'tutor')
                        ->first();

                    $p->avaliacao_do_tutor = $avaliacaoTutor;
                    $p->avaliado_pelo_tutor = (bool) $avaliacaoTutor;

                    return $p;
                });

        }

        if ($user->tipo_usuario === 'passeador') {

            return Passeio::where('passeador_id', $user->id)
                ->whereIn('status', ['aceito', 'finalizado'])
                ->with(['dog', 'tutor'])
                ->get()
                ->map(function ($p) {

                    $avaliacaoPasseador = \App\Models\Avaliacao::where('passeio_id', $p->id)
                        ->where('tipo_avaliador', 'passeador')
                        ->first();

                    $p->avaliacao_do_passeador = $avaliacaoPasseador;
                    $p->avaliado_pelo_passeador = (bool) $avaliacaoPasseador;

                    return $p;
                });

        }

        return collect();
    }
    public function excluir(int $id)
    {
        $passeio = Passeio::findOrFail($id);

        $passeio->delete();
    }
}