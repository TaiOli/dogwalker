<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private User $model
    ) {}

    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function findById(int $id): ?User
    {
        return $this->model->find($id);
    }

    public function getWalkersWithRatingAvg(): Collection
    {
        return $this->model
            ->where('tipo_usuario', 'passeador')
            ->withAvg('receivedEvaluations', 'nota')
            ->get();
    }

    public function findWithReceivedEvaluations(int $id): User
    {
        return $this->model
            ->where('id', $id)
            ->with(['receivedEvaluations' => function ($q) {
                $q->where('tipo_avaliador', 'tutor')
                    ->with('tutor')
                    ->latest();
            }])
            ->withAvg('receivedEvaluations', 'nota')
            ->firstOrFail();
    }

    public function findWithSubmittedEvaluations(int $id): User
    {
        return $this->model
            ->where('id', $id)
            ->with(['givenEvaluations' => function ($q) {
                $q->where('tipo_avaliador', 'passeador')
                    ->with('passeador')
                    ->latest();
            }])
            ->firstOrFail();
    }

    public function update(int $id, array $data): User
    {
        $user = $this->findById($id);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return $user;
    }
}