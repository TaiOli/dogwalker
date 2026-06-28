<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    protected $table = 'avaliacoes';

    protected $fillable = [
        'passeio_id',
        'tutor_id',
        'passeador_id',
        'nota',
        'comentario',
        'tipo_avaliador',
    ];

    // RELACIONAMENTOS

    public function passeio()
    {
        return $this->belongsTo(Passeio::class);
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function passeador()
    {
        return $this->belongsTo(User::class, 'passeador_id');
    }
}