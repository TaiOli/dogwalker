<?php

namespace App\Repositories\Eloquent;

use App\Enums\TipoUsuario;
use App\Models\Log;
use App\Models\User;
use App\Repositories\Contracts\LogRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LogRepository implements LogRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function create(array $data): Log
    {
        return Log::query()->create($data);
    }

    public function getPaginated(User $viewer, array $filters, int $perPage = 50): LengthAwarePaginator
    {
        $query = Log::query()->with((['user']));

        if ($viewer->tipo === TipoUsuario::TUTOR) {
            $query->where('user_id', $viewer->id);
        }

        if ($viewer->tipo === TipoUsuario::PASSEADOR->value) {
            $query->where('user_id', $viewer->id);
        }

        if (!empty($filters['action'])) {
            $query->where('action', 'like', '%' . $filters['action'] . '%');
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['user'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['user'] . '%');
            });
        }

        return $query
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }
}