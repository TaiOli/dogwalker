<?php

namespace App\Services;

use App\Models\Dog;

class DogService
{
    public function create(array $data, int $userId)
    {
        return Dog::create([
            ...$data,
            'user_id' => $userId
        ]);
    }

    public function myDogs(int $userId)
    {
        return Dog::where('user_id', $userId)->get();
    }
}