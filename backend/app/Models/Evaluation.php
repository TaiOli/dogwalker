<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $table = 'avaliacoes';

    protected $fillable = [
        'passeio_id',
        'tutor_id',
        'passeador_id',
        'nota',
        'comentario',
        'tipo_avaliador',
    ];


    public function tours()
    {
        return $this->belongsTo(Tour::class);
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function walker()
    {
        return $this->belongsTo(User::class, 'passeador_id');
    }
}