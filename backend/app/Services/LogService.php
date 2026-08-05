<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\LogRepositoryInterface;
use App\Repositories\Services\Contracts\LogServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class LogService implements LogServiceInterface
{
    public function __construct(
        protected LogRepositoryInterface $logRepository
    ) {}

    public function log(string $action, ?int $userId = null, ?string $description = null): void
    {
        $user = Auth::user();

        $this->logRepository->create([
            'user_id' => $userId ?? ($user ? $user->id : null),
            'action' => $action,
            'description' => $description,
        ]);
    }

    public function getPaginatedLogs(
        User $viewer,
        array $filters,
        int $perPage = 50
    ): LengthAwarePaginator {
        return $this->logRepository->getPaginated(
            $viewer,
            $filters,
            $perPage
        );
    }
}