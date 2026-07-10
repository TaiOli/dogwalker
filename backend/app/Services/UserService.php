<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserUnauthorizedException;

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
                    'media_avaliacao' => round($user->receivedEvaluationsAvgRating ?? 0, 1),
                ];
            });
    }

    public function show(int $id): array
    {
        $user = $this->userRepository->findWithReceivedEvaluations($id);

        return [
            'id' => $user->id,
            'nome' => $user->nome,
            'email' => $user->email,
            'telefone' => $user->telefone,
            'foto' => $user->foto,
            'tipo_usuario' => $user->tipo_usuario,
            'received_reviews' => $user->receivedEvaluations->map(function ($review) {
                return [
                    'id' => $review->id,
                    'nota' => $review->nota,
                    'comentario' => $review->comentario,
                    'created_at' => $review->created_at,
                    'tutor' => $review->tutor ? [
                        'id' => $review->tutor->id,
                        'nome' => $review->tutor->nome,
                    ] : null,
                ];
            }),
        ];
    }
    
    public function showTutor(int $id): array
    {
        $user = $this->userRepository->findWithSubmittedEvaluations($id);

        return [
            'id' => $user->id,
            'nome' => $user->nome,
            'email' => $user->email,
            'telefone' => $user->telefone,
            'foto' => $user->foto,
            'tipo_usuario' => $user->tipo_usuario,
            'submitted_reviews' => $user->givenEvaluations->map(function ($review) {
                return [
                    'id' => $review->id,
                    'nota' => $review->nota,
                    'comentario' => $review->comentario,
                    'created_at' => $review->created_at,
                    'passeador' => $review->passeador ? [
                        'id' => $review->passeador->id,
                        'nome' => $review->passeador->nome,
                    ] : null,
                ];
            }),
        ];
    }

    public function update(int $id, array $data, int $authUserId): User
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        if ($user->id !== $authUserId) {
            throw new UserUnauthorizedException();
        }

        if (isset($data['foto'])) {
            $data['foto'] = $data['foto']->store('users', 'public');
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->userRepository->update($id, $data);
    }
}