<?php

namespace App\Observers;

use App\Models\Tour;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class TourObserver
{
    public function created(Tour $tour): void
    {
        Log::create([
            'user_id'     => Auth::id(),
            'action'      => 'Criar Passeio',
            'description' => "O usuário solicitou um novo passeio (ID: {$tour->id})."
        ]);
    }

    public function updated(Tour $tour): void
    {
        if ($tour->isDirty('status')) {
            // Tratamento do Enum usando ->value com fallback para string limpa
            $statusAntigo = $tour->getOriginal('status')?->value ?? 'Nenhum';
            $statusNovo   = $tour->status?->value ?? 'Nenhum';

            Log::create([
                'user_id'     => Auth::id(),
                'action'      => 'Atualizar Status do Passeio',
                'description' => "O status do passeio ID {$tour->id} mudou de '{$statusAntigo}' para '{$statusNovo}'."
            ]);
            return;
        }

        Log::create([
            'user_id'     => Auth::id(),
            'action'      => 'Editar Passeio',
            'description' => "O passeio ID {$tour->id} teve suas informações alteradas."
        ]);
    }

    public function deleted(Tour $tour): void
    {
        Log::create([
            'user_id'     => Auth::id(),
            'action'      => 'Excluir Passeio',
            'description' => "O passeio ID {$tour->id} foi removido do sistema."
        ]);
    }
}