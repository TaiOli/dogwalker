<?php

namespace App\Http\Controllers;

use App\DTOs\User\UpdateUserDTO;
use App\DTOs\User\UserResponseDTO;
use App\Http\Requests\StoreLoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserUnauthorizedException;
use App\Repositories\Services\Contracts\UserServiceInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private UserServiceInterface $userService
    ) {}

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create($request->toDto());

        return response()->json([
            'message' => 'Usuário criado com sucesso',
            'user'    => (new UserResponseDTO($user))->toArray(),
        ], 201);
    }

    public function login(StoreLoginRequest $request)
    {
        $result = $this->userService->login($request->validated());

        if (!$result) {
            return response()->json([
                'message' => 'Credenciais inválidas'
            ], 401);
        }

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'user' => $result['user'],
            'token' => $result['token']
        ],201);
    }

    public function walkers()
    {
        return response()->json(
            $this->userService->walkers() 
        );
    }

    public function show($id)
    {
        return response()->json(
            $this->userService->show($id) 
        );
    }

    public function showTutor($id)
    {
        return response()->json(
            $this->userService->showTutor($id) 
        );
    }

    public function update($id, UpdateUserRequest $request)
    {
        try {
            $dto  = UpdateUserDTO::fromRequest($request->validated());
            
            $user = $this->userService->update(
                $id, 
                $dto, 
                $request->user()->id
            );

            return response()->json([
                'message' => 'Usuário atualizado com sucesso',
                'user' => (new UserResponseDTO($user))->toArray(),
            ],200);

        } catch (UserNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        } catch (UserUnauthorizedException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 403);
        }
    }

    public function me(Request $request)
    {
        return response()->json(
            (new UserResponseDTO($request->user()))->toArray()
        );
    }
}