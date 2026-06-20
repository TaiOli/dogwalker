<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dog extends Model
{
    protected $fillable = [
        'user_id',
        'nome',
        'idade',
        'porte',
        'raca',
        'observacoes',
        'foto',
    ];
}
