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

    public function accept(int $id, int $walkerId)
    {
        $passeio = Tour::findOrFail($id);

        $passeio->update([
            'passeador_id' => $walkerId,
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

    public function cancel(int $id)
    {
        $tour = Tour::findOrFail($id);

        $tour->update([
            'status' => 'cancelado'
        ]);

        return $tour;
    }

    public function myTours($user)
    {
        if ($user->tipo_usuario === 'tutor') {

            return Tour::where('tutor_id', $user->id)
                ->with(['dog', 'passeador'])
                ->get()
                ->map(function ($p) {

                    $reviewTutor = Evaluation::where('passeio_id', $p->id)
                        ->where('tipo_avaliador', 'tutor')
                        ->first();

                    $p->review_by_tutor = $reviewTutor;
                    $p->rated_by_tutor = (bool) $reviewTutor;

                    return $p;
                });

        }

        if ($user->tipo_usuario === 'passeador') {

            return Tour::where('passeador_id', $user->id)
                ->whereIn('status', ['aceito', 'finalizado', 'cancelado'])
                ->with(['dog', 'tutor'])
                ->get()
                ->map(function ($p) {

                    $reviewWlaker = Evaluation::where('passeio_id', $p->id)
                        ->where('tipo_avaliador', 'passeador')
                        ->first();

                    $p->review_by_walker = $reviewWlaker;
                    $p->rated_by_walker = (bool) $reviewWlaker;

                    return $p;
                });

        }

        return collect();
    }
    public function delete(int $id)
    {
        $tour = Tour::findOrFail($id);

        $tour->delete();
    }
}