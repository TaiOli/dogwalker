<?php

namespace App\Http\Controllers;

use App\DTOs\Tour\TourResponseDTO;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTourRequest;
use App\Exceptions\TourNotFoundException;
use App\Exceptions\TourInvalidStatusException;
use App\Models\Tour;
use App\Repositories\Services\Contracts\TourServiceInterface;

class TourController extends Controller
{
    public function __construct(
        private TourServiceInterface $tourService
    ) {}

    // Criar Passeio
    public function store(StoreTourRequest $request)
    {
        $tour = $this->tourService->create($request->toDto());

        return response()->json([
            'message' => 'Passeio solicitado com sucesso!',
            'passeio' => (new TourResponseDTO($tour))->toArray(),
        ], 201);
    }

    // Listar Passeio
    public function index(Request $request)
    {
        return $this->tourService->listAvailable($request->user()->id);
    }

    public function accept($id, Request $request)
    {
        $tour = Tour::findOrFail($id);
        $this->authorize('accept', $tour);

        try {
            $tour = $this->tourService->accept($id, $request->user()->id);

            return response()->json([
                'message' => 'Passeio aceito com sucesso!',
                'passeio' => (new TourResponseDTO($tour))->toArray(),
            ], 200);
        } catch (TourInvalidStatusException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
    }

    public function reject($id, Request $request)
    {
        try {
            $tour = $this->tourService->reject($id);

            return response()->json([
                'message' => 'Passeio recusado!',
                'status' => $tour->status->value
            ], 200);
        } catch (TourNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (TourInvalidStatusException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
    }

    public function myTours(Request $request)
    {
        return response()->json(
            $this->tourService->myTours($request->user())
        );
    }

    public function cancel($id, Request $request)
    {
        $tour = Tour::findOrFail($id);
        $this->authorize('cancel', $tour);

        $tour = $this->tourService->cancel($id);

        return response()->json([
            'message' => 'Passeio cancelado com sucesso!',
            'passeio' => (new TourResponseDTO($tour))->toArray(),
        ], 200);
    }

    public function complete($id, Request $request)
    {
        $tour = Tour::findOrFail($id);
        $this->authorize('complete', $tour);

        try {
            $tour = $this->tourService->complete($id);

            return response()->json([
                'message' => 'Passeio finalizado com sucesso',
                'passeio' => (new TourResponseDTO($tour))->toArray(),
            ], 200);
        } catch (TourInvalidStatusException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
    }

    // Excluir Passeio
    public function destroy($id, Request $request)
    {
        $tour = Tour::findOrFail($id);
        $this->authorize('delete', $tour);

        $this->tourService->delete($id);

        return response()->json([
            'message' => 'Passeio removido com sucesso!'
        ], 200);
    }
}