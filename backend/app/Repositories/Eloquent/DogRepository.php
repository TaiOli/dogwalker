<?php

namespace App\Repositories\Eloquent;

use App\Models\Dog;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Contracts\DogRepositoryInterface;

class DogRepository implements DogRepositoryInterface
{
    public function create(array $data): Dog
    {
        return Dog::create($data);
    }

    // Filtro de busca única
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

    public function findById(int $id): ?Dog
    {
        return Dog::find($id);
    }

    public function update(int $id, array $data): Dog
    {
        $dog = Dog::findOrFail($id);
        $dog->update($data);
        return $dog;
    }

    public function delete(int $id): void
    {
        Dog::findOrFail($id)->delete();
    }

}