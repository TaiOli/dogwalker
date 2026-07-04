<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePasseioRequest;
use App\Models\Passeio;
use App\Services\TourService;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function __construct(
        private TourService  $passeioService
    ) {}

    // Criar Passeio
    public function store(StorePasseioRequest $request)
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

    public function myTours(Request $request)
    {
        return $this->passeioService->myTours(
            $request->user()
        );
    }

    public function complete($id)
    {
        $passeio = Passeio::findOrFail($id);

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