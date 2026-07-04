<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTourRequest;
use App\Models\Tour;
use App\Services\TourService;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function __construct(
        private TourService  $passeioService
    ) {}

    // Criar Passeio
    public function store(StoreTourRequest $request)
    {
        $passeio = $this->passeioService->create(
            $request->validated(),
            $request->user()->id
        );

        return response()->json([
            'message' => 'Passeio solicitado com sucesso',
            'passeio' => $passeio
        ]);
    }

    // Listar Passeio
    public function index()
    {
        return $this->passeioService->listAvailable();
    }

    public function accept($id, Request $request)
    {
        $passeio = $this->passeioService->accept(
            $id,
            $request->user()->id
        );

        return response()->json([
            'message' => 'Passeio aceito com sucesso',
            'passeio' => $passeio
        ]);
    }

    public function reject($id)
    {
        $passeio = $this->passeioService->reject($id);

        return response()->json([
            'message' => 'Passeio recusado',
            'status' => $passeio->status
        ]);
    }


    public function cancel($id, Request $request)
    {
        $passeio = Tour::findOrFail($id);

        if ($passeio->tutor_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Você não tem permissão para cancelar este passeio.'
            ], 403);
        }

        if (!in_array($passeio->status, ['pendente', 'aceito'])) {
            return response()->json([
                'message' => 'Só é possível cancelar passeios pendentes ou aceitos.'
            ], 422);
        }

        $passeio->update([
            'status' => 'cancelado'
        ]);

        return response()->json([
            'message' => 'Passeio cancelado com sucesso',
            'passeio' => $passeio
        ]);
    }

    public function myTours(Request $request)
    {
        return $this->passeioService->myTours(
            $request->user()
        );
    }

    public function complete($id)
    {
        $passeio = Tour::findOrFail($id);

        $passeio->update([
            'status' => 'finalizado'
        ]);

        return response()->json([
            'message' => 'Passeio finalizado com sucesso',
            'passeio' => $passeio
        ]);
    }

    // Excluir Passeio
    public function destroy($id)
    {
        $this->passeioService->delete($id);

        return response()->json([
            'message' => 'Passeio removido com sucesso'
        ]);
    }
}