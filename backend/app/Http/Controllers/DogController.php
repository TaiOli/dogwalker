<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDogRequest;
use App\Http\Requests\UpdateDogRequest;
use App\Http\Requests\SearchDogRequest;
use App\Services\DogService;
use App\Exceptions\DogNotFoundException;
use App\Exceptions\DogUnauthorizedException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        ], 201);
    }

    public function edit(UpdateDogRequest $request, $id)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('dogs', 'public');
                $data['foto'] = Storage::url($path);
            }

            $dog = $this->dogService->update(
                $data,
                $id,
                $request->user()->id
            );

            return response()->json([
                'message' => 'Cachorro atualizado com sucesso',
                'dog' => $dog
            ],200);
        // Not Found : Página ou dado procurado não existe
        }catch (DogNotFoundException $e) {
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
            $this->dogService->delete(
                $id,
                $request->user()->id
            );

            return response()->json([
                'message' => 'Cachorro removido com sucesso'
            ], 200);

        } catch (DogNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);

        } catch (DogUnauthorizedException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 403);
        }
    }

    public function myDogs(SearchDogRequest $request)
    {
        $data = $request->validated();

        return $this->dogService->myDogs($data);
    }
}