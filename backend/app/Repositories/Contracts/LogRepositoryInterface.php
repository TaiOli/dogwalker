<?php

namespace App\Repositories\Contracts;

use App\Models\Log;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LogRepositoryInterface
{
    public function create(array $data): Log;
    public function getPaginated(User $viewer, array $filters, int $perPage = 50):LengthAwarePaginator;
}
