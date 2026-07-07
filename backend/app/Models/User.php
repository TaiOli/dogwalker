<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nome',
        'telefone',
        'tipo_usuario',
        'foto',
    ];
    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function dogs()
    {
        return $this->hasMany(Dog::class);
    }

    public function passeiosComoTutor()
    {
        return $this->hasMany(Tour::class, 'tutor_id');
    }

    public function avaliacoesRecebidas()
    {
        return $this->hasMany(Evaluation::class, 'passeador_id');
    }

    public function avaliacoesFeitas()
    {
        return $this->hasMany(Evaluation::class, 'tutor_id');
    }
}
