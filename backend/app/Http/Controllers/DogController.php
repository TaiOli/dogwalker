<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use App\DTOs\Dog\UpdateDogDTO;
use App\DTOs\Dog\DogResponseDTO;
use App\Http\Requests\StoreDogRequest;
use App\Http\Requests\UpdateDogRequest;
use App\Http\Requests\SearchDogRequest;
use App\Repositories\Services\Contracts\DogServiceInterface;

class DogController extends Controller
{
    public function __construct(
        private DogServiceInterface $dogService
    ) {}

    // Cadastrar cachorro
    public function store(StoreDogRequest $request)
    {
        $dog = $this->dogService->create($request->toDto());

        return response()->json([
            'message' => 'Cachorro cadastrado com sucesso',
            'dog'     => (new DogResponseDTO($dog))->toArray(),
        ], 201);
        // Status 201: Sucesso na criação
    }


    // Editar dados do cachorro
    public function edit(UpdateDogRequest $request, $id)
    {
        $dog = Dog::findOrFail($id);
        $this->authorize('update', $dog);

        $dto = UpdateDogDTO::fromRequest($request->validated());
        $dog = $this->dogService->update($dto, $id);

        return response()->json([
            'message' => 'Cadastro do cachorro atualizado com sucesso!',
            'dog'     => (new DogResponseDTO($dog))->toArray(),
        ], 200);
    }

    // Excluir cadastro do cachorro
    public function destroy($id)
    {
        $dog = Dog::findOrFail($id);
        $this->authorize('delete', $dog);

        $this->dogService->delete($id);

        return response()->json(['message' => 'Cadastro do cachorro removido com sucesso!'], 200);
    }

    // Listar dados do cachorro
    public function myDogs(SearchDogRequest $request)
    {
        $dogs = $this->dogService->myDogs($request->validated());

        return response()->json(
            DogResponseDTO::collection($dogs)
        );
    }
}