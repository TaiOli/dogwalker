<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passeio extends Model
{
    protected $fillable = [
        'dog_id',
        'tutor_id',
        'passeio_id',
        'data',
        'hora',
        'duracao',
        'local',
        'valor',
        'status',
    ];
}
