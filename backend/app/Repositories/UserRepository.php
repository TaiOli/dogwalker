<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\UserRepositoryInterface;

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
            ->withAvg('avaliacoesRecebidas', 'nota')
            ->get();
    }

    public function findWithReceivedEvaluations(int $id): User
    {
        return $this->model
            ->where('id', $id)
            ->with(['avaliacoesRecebidas' => function ($q) {
                $q->where('tipo_avaliador', 'tutor')
                    ->with('tutor')
                    ->latest();
            }])
            ->withAvg('avaliacoesRecebidas', 'nota')
            ->firstOrFail();
    }

    public function findWithSubmittedEvaluations(int $id): User
    {
        return $this->model
            ->where('id', $id)
            ->with(['avaliacoesFeitas' => function ($q) {
                $q->where('tipo_avaliador', 'passeador')
                    ->with('passeador')
                    ->latest();
            }])
            ->firstOrFail();
    }
}