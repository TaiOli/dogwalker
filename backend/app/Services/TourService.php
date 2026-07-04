<?php

namespace App\Services;

use App\Http\Controllers\TourController;
use App\Models\Evaluation;
use App\Models\Tour;

class TourService
{
    public function create(array $data, int $tutorId)
    {
        return Tour::create([
            ...$data,
            'tutor_id' => $tutorId,
            'status' => 'pendente',
        ]);
    }

    public function listAvailable()
    {
        return Tour::with(['dog', 'tutor'])
            ->whereIn('status', ['pendente', 'aceito'])
            ->latest()
            ->get();
    }

    public function accept(int $id, int $passeadorId)
    {
        $passeio = Tour::findOrFail($id);

        $passeio->update([
            'passeador_id' => $passeadorId,
            'status' => 'aceito'
        ]);

        return $passeio;
    }

    public function reject(int $id)
    {
        $passeio = Tour::findOrFail($id);

        $passeio->update([
            'status' => 'recusado'
        ]);

        return $passeio->fresh();
    }

    public function myTours($user)
    {
        if ($user->tipo_usuario === 'tutor') {

            return Tour::where('tutor_id', $user->id)
                ->with(['dog', 'passeador'])
                ->get()
                ->map(function ($p) {

                    $avaliacaoTutor = Evaluation::where('passeio_id', $p->id)
                        ->where('tipo_avaliador', 'tutor')
                        ->first();

                    $p->avaliacao_do_tutor = $avaliacaoTutor;
                    $p->avaliado_pelo_tutor = (bool) $avaliacaoTutor;

                    return $p;
                });

        }

        if ($user->tipo_usuario === 'passeador') {

            return Tour::where('passeador_id', $user->id)
                ->whereIn('status', ['aceito', 'finalizado', 'cancelado'])
                ->with(['dog', 'tutor'])
                ->get()
                ->map(function ($p) {

                    $avaliacaoPasseador = Evaluation::where('passeio_id', $p->id)
                        ->where('tipo_avaliador', 'passeador')
                        ->first();

                    $p->avaliacao_do_passeador = $avaliacaoPasseador;
                    $p->avaliado_pelo_passeador = (bool) $avaliacaoPasseador;

                    return $p;
                });

        }

        return collect();
    }
    public function delete(int $id)
    {
        $passeio = Tour::findOrFail($id);

        $passeio->delete();
    }
}