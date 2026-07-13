<?php

namespace App\DTOs\User;

use App\Models\User;

class WalkerProfileResponseDTO
{
    public readonly int    $id;
    public readonly string $nome;
    public readonly string $email;
    public readonly ?string $telefone;
    public readonly ?string $foto;
    public readonly ?float  $mediaAvaliacao;
    public readonly array   $receivedReviews;

    public function __construct(User $user)
    {
        $this->id           = $user->id;
        $this->nome         = $user->nome;
        $this->email        = $user->email;
        $this->telefone     = $user->telefone;
        $this->foto         = $user->foto;
        $this->mediaAvaliacao = isset($user->received_evaluations_avg_nota)
            ? round((float) $user->received_evaluations_avg_nota, 1)
            : null;

        $this->receivedReviews = $user->receivedEvaluations
            ->map(fn($review) => [
                'id'         => $review->id,
                'nota'       => $review->nota,
                'comentario' => $review->comentario,
                'created_at' => $review->created_at->toISOString(),
                'tutor'      => $review->tutor ? [
                    'id'   => $review->tutor->id,
                    'nome' => $review->tutor->nome,
                ] : null,
            ])
            ->values()
            ->all();
    }

    public function toArray(): array
    {
        return [
            'id'              => $this->id,
            'nome'            => $this->nome,
            'email'           => $this->email,
            'telefone'        => $this->telefone,
            'foto'            => $this->foto,
            'media_avaliacao' => $this->mediaAvaliacao,
            'received_reviews' => $this->receivedReviews,
        ];
    }
}