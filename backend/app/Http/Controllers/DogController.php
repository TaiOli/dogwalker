<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDogRequest;
use App\Models\Dog;
use Illuminate\Http\Request;

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

    public function myDogs(Request $request)
    {
        return Dog::where('user_id', $request->user()->id)->get();
    }
}
