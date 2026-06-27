<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function create(array $data)
    {
        if (isset($data['foto'])) {
            $data['foto'] = $data['foto']->store('users', 'public');
        }

        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }

    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return null;
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function walkers()
    {
        return User::where('tipo_usuario', 'passeador')
            ->select('id', 'nome', 'telefone', 'foto')
            ->get();
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }
}