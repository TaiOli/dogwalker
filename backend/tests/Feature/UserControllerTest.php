<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Enums\TipoUsuario;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Services\Contracts\UserServiceInterface;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $userServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userServiceMock = $this->mock(UserServiceInterface::class);
    }

    public function test_create_user(): void
    {
        $userData = [
            'username'     => 'tais',
            'nome'         => 'Tais Oliveira',
            'email'        => 'tais@example.com',
            'password'     => '123456',
            'tipo_usuario' => TipoUsuario::TUTOR->value,
            'telefone'     => '71999999999',
        ];

        $createdUser = new User($userData);
        $createdUser->id = 1;

        $this->userServiceMock
            ->shouldReceive('create')
            ->once()
            ->andReturn($createdUser);

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
            ->assertJsonFragment(['message' => 'Usuário criado com sucesso!']);
    }

    public function test_user_update(): void
    {
        $user = User::factory()->create([
            'tipo_usuario' => TipoUsuario::TUTOR->value,
        ]);

        Sanctum::actingAs($user);

        $updateData = [
            'nome' => 'Novo Nome',
            'telefone' => '71988888888',
        ];

        $updatedUser = new User(array_merge($user->toArray(), $updateData));
        $updatedUser->id = $user->id;

        $this->userServiceMock
            ->shouldReceive('update')
            ->once()
            ->andReturn($updatedUser);

        $response = $this->putJson("/api/users/{$user->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Usuário atualizado com sucesso']);
    }

    public function test_user_editing_another_user(): void
    {
        $user = User::factory()->create([
            'tipo_usuario' => TipoUsuario::TUTOR->value,
        ]);

        $otherUser = User::factory()->create([
            'tipo_usuario' => TipoUsuario::TUTOR->value,
        ]);

        Sanctum::actingAs($user);

        $updateData = [
            'nome' => 'Tentando Editar Outro',
        ];

        $response = $this->putJson("/api/users/{$otherUser->id}", $updateData);

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_update(): void
    {
        $user = User::factory()->create();

        $response = $this->putJson("/api/users/{$user->id}", [
            'nome' => 'Sem Login',
        ]);

        $response->assertStatus(401);
    }

    public function authenticated_user_test(): void
    {
        $user = User::factory()->create([
            'tipo_usuario' => TipoUsuario::TUTOR->value,
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/me');

        $response->assertStatus(200)
            ->assertJsonFragment(['email' => $user->email]);
    }
}