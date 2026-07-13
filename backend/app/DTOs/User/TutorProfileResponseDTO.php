<?php

namespace App\DTOs\User;

use App\Models\User;

class TutorProfileResponseDTO
{
    public readonly int     $id;
    public readonly string  $nome;
    public readonly string  $email;
    public readonly ?string $telefone;
    public readonly ?string $foto;
    public readonly array   $submittedReviews;

    public function __construct(User $user)
    {
        $this->id    = $user->id;
        $this->nome  = $user->nome;
        $this->email = $user->email;
        $this->telefone = $user->telefone;
        $this->foto  = $user->foto;

        $this->submittedReviews = $user->givenEvaluations
            ->map(fn($review) => [
                'id'         => $review->id,
                'nota'       => $review->nota,
                'comentario' => $review->comentario,
                'created_at' => $review->created_at->toISOString(),
                'passeador'  => $review->walker ? [
                    'id'   => $review->walker->id,
                    'nome' => $review->walker->nome,
                ] : null,
            ])
            ->values()
            ->all();
    }

    public function toArray(): array
    {
        return [
            'id'               => $this->id,
            'nome'             => $this->nome,
            'email'            => $this->email,
            'telefone'         => $this->telefone,
            'foto'             => $this->foto,
            'submitted_reviews' => $this->submittedReviews,
        ];
    }
}