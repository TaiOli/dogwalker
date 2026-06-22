<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDogRequest;
use App\Models\Dog;

class DogController extends Controller
{
    public function store(StoreDogRequest $request)
    {
        $dog = Dog::create([
            ...$request->validated(),
            'user_id' => $request->user()->id
        ]);

        return response()->json([
            'message' => 'Cachorro cadastrado com sucesso',
            'dog' => $dog
        ]);
    }
}
