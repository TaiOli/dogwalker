<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTourRequest;
use App\Models\Tour;
use App\Services\TourService;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function __construct(
        private TourService  $tourService
    ) {}

    // Criar Passeio
    public function store(StoreTourRequest $request)
    {
        $tour = $this->tourService->create(
            $request->validated(),
            $request->user()->id
        );

        return response()->json([
            'message' => 'Passeio solicitado com sucesso',
            'passeio' => $tour
        ]);
    }

    // Listar Passeio
    public function index()
    {
        return $this->tourService->listAvailable();
    }

    public function accept($id, Request $request)
    {
        $tour = $this->tourService->accept(
            $id,
            $request->user()->id
        );

        return response()->json([
            'message' => 'Passeio aceito com sucesso',
            'passeio' => $tour
        ]);
    }

    public function reject($id)
    {
        $tour = $this->tourService->reject($id);

        return response()->json([
            'message' => 'Passeio recusado',
            'status' => $tour->status
        ]);
    }

    public function myTours(Request $request)
    {
        return $this->tourService->myTours(
            $request->user()
        );
    }

    public function complete($id)
    {
        $tour = Tour::findOrFail($id);

        $tour->update([
            'status' => 'finalizado'
        ]);

        return response()->json([
            'message' => 'Passeio finalizado com sucesso',
            'passeio' => $tour
        ]);
    }

    // Excluir Passeio
    public function destroy($id)
    {
        $this->tourService->delete($id);

        return response()->json([
            'message' => 'Passeio removido com sucesso'
        ]);
    }
}