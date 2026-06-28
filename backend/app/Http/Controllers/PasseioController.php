<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePasseioRequest;
use App\Services\PasseioService;
use Illuminate\Http\Request;

class PasseioController extends Controller
{
    public function __construct(
        private PasseioService  $passeioService
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
        return $this->passeioService->listarDisponiveis();
    }

    public function aceitar($id, Request $request)
    {
        $passeio = $this->passeioService->aceitar(
            $id,
            $request->user()->id
        );

        return response()->json([
            'message' => 'Passeio aceito com sucesso',
            'passeio' => $passeio
        ]);
    }

    public function recusar($id)
    {
        $passeio = $this->passeioService->recusar($id);

        return response()->json([
            'message' => 'Passeio recusado',
            'status' => $passeio->status
        ]);
    }

    public function meusPasseios(Request $request)
    {
        return $this->passeioService->meusPasseios(
            $request->user()
        );
    }

    public function finalizar($id)
    {
        $passeio = \App\Models\Passeio::findOrFail($id);

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
        $this->passeioService->excluir($id);

        return response()->json([
            'message' => 'Passeio removido com sucesso'
        ]);
    }
}