<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\DogPorte;

class Dog extends Model
{
    protected $table = 'dogs';

    protected $fillable = [
        'user_id',
        'nome',
        'idade',
        'porte',
        'raca',
        'observacoes',
        'foto',
    ];

    protected function casts(): array
    {
        return [
            'idade' => 'integer',
            'porte' => DogPorte::class,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
