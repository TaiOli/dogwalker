<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function create(array $data): User
    {
        if (isset($data['foto'])) {
            $data['foto'] = $data['foto']->store('users', 'public');
        }

        $data['password'] = Hash::make($data['password']);

        return $this->userRepository->create($data);
    }

    public function login(array $data): ?array
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return null;
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function walkers(): Collection
    {
        return $this->userRepository
            ->getWalkersWithRatingAvg()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'nome' => $user->nome,
                    'telefone' => $user->telefone,
                    'foto' => $user->foto,
                    'media_avaliacao' => round($user->avaliacoesRecebidas_avg_nota ?? 0, 1),
                ];
            });
    }

    public function show(int $id): User
    {
        return $this->userRepository->findWithReceivedEvaluations($id);
    }

    public function showTutor(int $id): User
    {
        return $this->userRepository->findWithSubmittedEvaluations($id);
    }

    public function update(int $id, array $data): User
    {
        if (isset($data['foto'])) {
            $data['foto'] = $data['foto']->store('users', 'public');
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->userRepository->update($id, $data);
    }
}