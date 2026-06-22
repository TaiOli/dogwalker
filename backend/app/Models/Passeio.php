<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passeio extends Model
{
    protected $fillable = [
        'dog_id',
        'tutor_id',
        'passeador_id',
        'data',
        'hora',
        'duracao',
        'local',
        'valor',
        'status',
    ];

    public function dog()
    {
        return $this->belongsTo(Dog::class);
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }
}
