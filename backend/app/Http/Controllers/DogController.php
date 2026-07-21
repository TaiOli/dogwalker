<?php

namespace App\Http\Controllers;


use App\DTOs\Dog\UpdateDogDTO;
use App\DTOs\Dog\DogResponseDTO;
use App\Http\Requests\StoreDogRequest;
use App\Http\Requests\UpdateDogRequest;
use App\Http\Requests\SearchDogRequest;
use App\Exceptions\DogNotFoundException;
use App\Exceptions\DogUnauthorizedException;
use App\Repositories\Services\Contracts\DogServiceInterface;
use Illuminate\Http\Request;

class DogController extends Controller
{
    public function __construct(
        private DogServiceInterface $dogService
    ) {}

    public function store(StoreDogRequest $request)
    {
        $dog = $this->dogService->create($request->toDto());

        return response()->json([
            'message' => 'Cachorro cadastrado com sucesso',
            'dog'     => (new DogResponseDTO($dog))->toArray(),
        ], 201);
        // Status 201: Sucesso na criação
    }


    public function edit(UpdateDogRequest $request, $id)
    {
        try {
            $dto = UpdateDogDTO::fromRequest(
                $request->validated()
            );

            $dog = $this->dogService->update($dto, $id, $request->user()->id);

            return response()->json([
                'message' => 'Cachorro atualizado com sucesso',
                'dog'     => (new DogResponseDTO($dog))->toArray(),
            ], 200);
            // Status 200: Sucesso
        } catch (DogNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
            // Status 404: Página ou dado procurado não existe
        } catch (DogUnauthorizedException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 403);
            // Status 403: Tentou alterar mas não pode
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