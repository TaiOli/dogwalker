<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Dog;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Services\Contracts\DogServiceInterface;

class DogControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $dogServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dogServiceMock = $this->mock(DogServiceInterface::class);
    }

    public function create_dog_test()
    {
        $dogData = ['name' => 'Rex', 'breed' => 'Labrador'];
        $createdDog = new Dog($dogData);
        $createdDog->id = 1;

        $this->dogServiceMock
            ->shouldReceive('create')
            ->once()
            ->andReturn($createdDog);

        $response = $this->postJson('/api/dogs', $dogData);

        $response->assertStatus(201)
            ->assertJsonFragment(['message' => 'Cachorro cadastrado com sucesso!']);
    }

    public function dog_editing_test()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $dog = Dog::factory()->create(['user_id' => $user->id]);
        $updateData = ['name' => 'Max'];

        $updatedDog = new Dog(array_merge($dog->toArray(), $updateData));

        $this->dogServiceMock
            ->shouldReceive('update')
            ->once()
            ->andReturn($updatedDog);

        $response = $this->putJson("/api/dogs/{$dog->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Cadastro do cachorro atualizado com sucesso!']);
    }

    public function dog_removal_test()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $dog = Dog::factory()->create(['user_id' => $user->id]);

        $this->dogServiceMock
            ->shouldReceive('delete')
            ->once()
            ->with((string) $dog->id)
            ->andReturn(true);

        $response = $this->deleteJson("/api/dogs/{$dog->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Cadastro do cachorro removido com sucesso!']);
    }

    public function dog_list_test()
    {
        $dogs = collect([new Dog(['name' => 'Rex'])]);

        $this->dogServiceMock
            ->shouldReceive('myDogs')
            ->once()
            ->andReturn($dogs);

        $response = $this->getJson('/api/dogs');

        $response->assertStatus(200);
    }
}