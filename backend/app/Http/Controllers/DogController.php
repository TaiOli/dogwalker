<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDogRequest;
use App\Services\DogService;
use Illuminate\Http\Request;

class DogController extends Controller
{
    public function __construct(
        private DogService $dogService
    ) {}

    public function store(StoreDogRequest $request)
    {
        $dog = $this->dogService->create(
            $request->validated(),
            $request->user()->id
        );

        return response()->json([
            'message' => 'Cachorro cadastrado com sucesso',
            'dog' => $dog
        ]);
    }

    public function myDogs(Request $request)
    {
        return $this->dogService->myDogs(
            $request->user()->id
        );
    }
}