<?php

namespace App\Models;

use App\Enums\TipoUsuario;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['username', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements CanResetPasswordContract
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, CanResetPasswordTrait;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected $table = 'users';

    protected $fillable = [
        'username',
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
            'tipo_usuario' => TipoUsuario::class
        ];
    }

    public function dogs()
    {
        return $this->hasMany(Dog::class);
    }

    public function tutorTours()
    {
        return $this->hasMany(Tour::class, 'tutor_id');
    }

    public function receivedEvaluations()
    {
        return $this->hasMany(Evaluation::class, 'passeador_id');
    }

    public function givenEvaluations()
    {
        return $this->hasMany(Evaluation::class, 'tutor_id');
    }
}