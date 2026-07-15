<?php

namespace App\Services;

use App\DTOs\User\CreateUserDTO;
use App\DTOs\User\UpdateUserDTO;
use App\DTOs\User\UserResponseDTO;
use App\DTOs\User\WalkerProfileResponseDTO;
use App\DTOs\User\TutorProfileResponseDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserUnauthorizedException;
use App\Repositories\Services\Contracts\UserServiceInterface;

class UserService implements UserServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function create(CreateUserDTO $dto): User
    {
        $foto = null;
        if ($dto->foto) {
            $foto = $dto->foto->store('users', 'public');
        }

        return $this->userRepository->create([
            'nome'        => $dto->nome,
            'email'       => $dto->email,
            'password'    => Hash::make($dto->password),
            'tipo_usuario' => $dto->tipoUsuario,
            'telefone'    => $dto->telefone,
            'foto'        => $foto,
        ]);
    }

    public function login(array $data): ?array
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return null;
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user'  => (new UserResponseDTO($user))->toArray(),
            'token' => $token,
        ];
    }

    public function walkers(): array
    {
        $walkers = $this->userRepository->getWalkersWithRatingAvg();

        return UserResponseDTO::collection($walkers);
    }

    public function show(int $id): array
    {
        $user = $this->userRepository->findWithReceivedEvaluations($id);

        return (new WalkerProfileResponseDTO($user))->toArray();
    }

    public function showTutor(int $id): array
    {
        $user = $this->userRepository->findWithSubmittedEvaluations($id);

        return (new TutorProfileResponseDTO($user))->toArray();
    }

    public function update(int $id, UpdateUserDTO $dto, int $authUserId): User
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        if ($user->id !== $authUserId) {
            throw new UserUnauthorizedException();
        }

        $data = $dto->toArray();

        if (isset($data['foto'])) {
            $data['foto'] = $data['foto']->store('users', 'public');
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->userRepository->update($id, $data);
    }
}