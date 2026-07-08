<?php

namespace App\Repositories;

use App\Models\Dog;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\DogRepositoryInterface;

class DogRepository implements DogRepositoryInterface
{
    public function create(array $data): Dog
    {
        return Dog::create($data);
    }

    public function findByUserId(int $userId, ?string $search = null): Collection
    {
        return Dog::where('user_id', $userId)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nome', 'like', "%{$search}%")
                    ->orWhere('raca', 'like', "%{$search}%");
                });
            })
            ->get();
    }
}