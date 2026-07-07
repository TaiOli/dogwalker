<?php

namespace App\Services;

use App\Models\Dog;
use Illuminate\Database\Eloquent\Collection;

class DogService
{
    public function create(array $data, int $userId):Dog
    {
        return Dog::create([
            ...$data,
            'user_id' => $userId
        ]);
    }

    public function myDogs(int $userId): Collection
    {
        return Dog::where('user_id', $userId)->get();
    }
}