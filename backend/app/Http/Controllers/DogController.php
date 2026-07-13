<?php

namespace App\Http\Controllers;

use App\DTOs\Dog\CreateDogDTO;
use App\DTOs\Dog\UpdateDogDTO;
use App\DTOs\Dog\DogResponseDTO;
use App\Http\Requests\StoreDogRequest;
use App\Http\Requests\UpdateDogRequest;
use App\Http\Requests\SearchDogRequest;
use App\Services\DogService;
use App\Exceptions\DogNotFoundException;
use App\Exceptions\DogUnauthorizedException;
use Illuminate\Http\Request;

class DogController extends Controller
{
    public function __construct(
        private DogService $dogService
    ) {}

    public function store(StoreDogRequest $request)
    {
        $dto = CreateDogDTO::fromRequest(
            $request->validated(),
            $request->user()->id
        );

        $dog = $this->dogService->create($dto);

        return response()->json([
            'message' => 'Cachorro cadastrado com sucesso',
            'dog'     => (new DogResponseDTO($dog))->toArray(),
        ], 201);
    }

    public function edit(UpdateDogRequest $request, $id)
    {
        try {
            $dto = UpdateDogDTO::fromRequest($request->validated());

            $dog = $this->dogService->update($dto, $id, $request->user()->id);

            return response()->json([
                'message' => 'Cachorro atualizado com sucesso',
                'dog'     => (new DogResponseDTO($dog))->toArray(),
            ], 200);
        // Not Found : Página ou dado procurado não existe
        } catch (DogNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        // Forbidden: Tentou alterar mas não pode
        } catch (DogUnauthorizedException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 403);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $this->dogService->delete($id, $request->user()->id);

            return response()->json(['message' => 'Cachorro removido com sucesso'], 200);

        } catch (DogNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (DogUnauthorizedException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    public function myDogs(SearchDogRequest $request)
    {
        $dogs = $this->dogService->myDogs($request->validated());

        return response()->json(
            DogResponseDTO::collection($dogs)
        );
    }
}