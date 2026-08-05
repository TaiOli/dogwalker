<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\JsonResponse;

class LogController extends Controller
{
    /**
     * Retorna a listagem de logs paginada.
     */
    public function index(): JsonResponse
    {
        // Retorna os logs mais recentes com os dados de quem executou a ação
        $logs = Log::with('user:id,name,email')
            ->latest()
            ->paginate(15);

        return response()->json($logs);
    }
}