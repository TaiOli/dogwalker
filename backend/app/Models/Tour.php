<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $table = 'passeios';
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

    public function walker()
    {
        return $this->belongsTo(User::class, 'passeador_id');
    }
}