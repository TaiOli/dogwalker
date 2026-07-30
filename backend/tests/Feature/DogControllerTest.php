<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Dog;
use App\Models\User;
use App\Enums\DogPorte;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
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

    public function test_create_dog()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $casos = DogPorte::cases();
        $porteString = isset($casos[0]) ? $casos[0]->value : 'medio';

        $dogData = [
            'nome' => 'Rex',
            'raca' => 'Labrador',
            'porte' => $porteString,
            'idade' => 3
        ];

        $createdDog = new Dog($dogData);
        $createdDog->id = 1;
        $createdDog->user_id = $user->id;
        $createdDog->porte = $porteString; 

        $this->dogServiceMock
            ->shouldReceive('create')
            ->once()
            ->andReturn($createdDog);

        $response = $this->postJson('/api/dogs', $dogData);

        $response->assertStatus(201)
            ->assertJsonFragment(['message' => 'Cachorro cadastrado com sucesso']);
    }

    public function test_dog_editing()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $dog = Dog::factory()->create(['user_id' => $user->id]);

        $casos = DogPorte::cases();
        $porteString = isset($casos[0]) ? $casos[0]->value : 'medio';

        $updateData = [
            'nome' => 'Max',
            'raca' => 'Labrador',
            'porte' => $porteString,
            'idade' => 4
        ];

        $updatedDog = new Dog(array_merge($dog->toArray(), $updateData));
        $updatedDog->id = $dog->id;
        $updatedDog->user_id = $user->id;
        $updatedDog->porte = $porteString;

        $this->dogServiceMock
            ->shouldReceive('update')
            ->once()
            ->andReturn($updatedDog);

        $response = $this->putJson("/api/dogs/{$dog->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Cadastro do cachorro atualizado com sucesso!']);
    }

    public function test_dog_removal()
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


    public function test_dog_list()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $casos = DogPorte::cases();
        $porteString = isset($casos[0]) ? $casos[0]->value : 'medio';

        $dogData = [
            'nome' => 'Rex',
            'raca' => 'Poodle',
            'porte' => $porteString,
            'idade' => 2,
            'user_id' => $user->id
        ];

        $dog = new Dog($dogData);
        $dog->id = 1;

        $dogs = new EloquentCollection([$dog]);

        $this->dogServiceMock
            ->shouldReceive('myDogs')
            ->once()
            ->andReturn($dogs);

        $response = $this->getJson('/api/dogs/my');

        $response->assertStatus(200);
    }
}
