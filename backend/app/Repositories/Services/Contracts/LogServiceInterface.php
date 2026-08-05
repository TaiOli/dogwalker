<?php

namespace App\Repositories\Services\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LogServiceInterface
{
    public function log(string $action, ?int $userId = null, ?string $description = null): void;
    public function getPaginatedLogs(User $viewer, array $filters, int $perPage = 50): LengthAwarePaginator;
}