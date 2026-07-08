<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDogRequest;
use App\Http\Requests\SearchDogRequest;
use App\Services\DogService;

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

    public function myDogs(SearchDogRequest $request)
    {
        $data = $request->validated();

        return $this->dogService->myDogs($data);
    }
}