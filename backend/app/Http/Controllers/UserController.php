<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\DTOs\User\UpdateUserDTO;
use App\DTOs\User\UserResponseDTO;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\Services\Contracts\UserServiceInterface;
use App\Exceptions\UserNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct(
        private UserServiceInterface $userService
    ) {}

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create($request->toDto());

        return response()->json([
            'message' => 'Usuário criado com sucesso!',
            'user'    => (new UserResponseDTO($user))->toArray(),
        ], 201);
    }

    public function login(StoreLoginRequest $request)
    {
        $result = $this->userService->login($request->validated());

        if (!$result) {
            return response()->json([
                'message' => 'Credenciais inválidas!'
            ], 401);
        }

        return response()->json([
            'message' => 'Login realizado com sucesso!',
            'user' => $result['user'],
            'token' => $result['token']
        ], 201);
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
        $targetUser = User::findOrFail($id);
        $this->authorize('update', $targetUser);

        try {
            $dto  = UpdateUserDTO::fromRequest($request->validated());
            $user = $this->userService->update($id, $dto);

            return response()->json([
                'message' => 'Usuário atualizado com sucesso',
                'user' => (new UserResponseDTO($user))->toArray(),
            ], 200);
        } catch (UserNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function me(Request $request)
    {
        return response()->json(
            (new UserResponseDTO($request->user()))->toArray()
        );
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        Password::sendResetLink($request->only('email'));
        return response()->json(status: Response::HTTP_OK);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password'  => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json(status: Response::HTTP_OK);
        }

        return response()->json(['message' => 'Erro ao redefinir a senha'], status: Response::HTTP_BAD_REQUEST);
    }
}