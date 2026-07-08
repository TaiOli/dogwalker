<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Usuário criado com sucesso',
            'user' => $user
        ]);
    }

    public function login(StoreLoginRequest $request)
    {
        $result = $this->userService->login(
            $request->validated()
        );

        if (!$result) {
            return response()->json([
                'message' => 'Credenciais inválidas'
            ], 401);
        }

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'user' => $result['user'],
            'token' => $result['token']
        ]);
    }

    public function walkers()
    {
        return $this->userService->walkers();
    }

    public function show($id)
    {
        return response()->json(
            $this->userService->show($id)
        );
    }

    public function showTutor($id)
    {
        return $this->userService->showTutor($id);
    }

    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->userService->update(
            $id,
            $request->validated()
        );

        return response()->json([
            'message' => 'Usuário atualizado com sucesso',
            'user' => $user
        ]);
    }
}