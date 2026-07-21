<?php

namespace App\DTOs\Evaluation;

class CreateEvaluationDTO
{
    public function __construct(
        public readonly int $passeioId,
        public readonly int $nota,
        public readonly string $tipoAvaliador,
        public readonly ?string $comentario = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            passeioId: $validated['passeio_id'],
            nota: $validated['nota'],
            tipoAvaliador: $validated['tipo_avaliador'],
            comentario: $validated['comentario'] ?? null,
        );
    }
}