<?php

namespace Tests\Integration;

use App\Models\User;
use App\Services\UserService;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected $userServiceMock;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionsSeeder::class);
        $this->seed(RolesSeeder::class);
        
        // Admin user setup
        $this->adminUser = User::factory()->create();

        Auth::login($this->adminUser);

        // Create a token for authentication
        $this->token = $this->adminUser->createToken('Test Token')->plainTextToken;

        $this->actingAs($this->adminUser);

        // Mock UserService
        $this->userServiceMock = Mockery::mock(UserService::class);
        $this->app->instance(UserService::class, $this->userServiceMock);
    }

    public function test_can_get_all_users()
    {
        $users = User::factory()->count(3)->create();

        // Mock getAll method
        $this->userServiceMock->shouldReceive('getAll')->once()->andReturn($users);

        $response = $this->get('/api/users');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonFragment(['id' => $users[0]->id]);
    }

    public function test_can_find_a_user()
    {
        $user = User::factory()->create();

        $this->userServiceMock->shouldReceive('find')->once()->with($user->id)->andReturn($user);

        $response = $this->get("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $user->id, 'name' => $user->name]);
    }

    public function test_can_create_user()
    {
        // Sample user data
        $userData = [
            'name' => 'fadi layyas',
            'email' => 'fadi@example.com',
            'password' => 'password123',
            'phone_number' => '0991833806',
            'is_active' => 1,
            'role' => 'Student'
        ];

        // Mock create method to return the user object
        $this->userServiceMock->shouldReceive('create')
            ->once()
            ->with($userData)
            ->andReturn(true);

        // Perform the request with headers
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->post('/api/users', $userData);

        // Assert the response status
        $response->assertStatus(200);

        $this->assertEquals(200, $response->json('statusCode'));
    }

    public function test_can_update_user()
    {
        $user = User::factory()->create([
            'name' => 'fadi alayyas',
            'email' => 'fadi@example.com',
            'password' => 'password123',
            'phone_number' => '0991833806',
            'is_active' => 1
        ]);

        $updatedData = [
            'name' => 'fadi',
            'email' => 'fadialayyas@gmail.com',
            'password' => 'password123',
            'phone_number' => '0991833806',
            'is_active' => 1,
            'role' => 'Student'
        ];

        $this->userServiceMock->shouldReceive('update')
            ->once()
            ->with($updatedData, $user->id)
            ->andReturn(true);

        $response = $this->put("/api/users/{$user->id}", $updatedData);

        $response->assertStatus(200);

        $this->assertEquals(200, $response->json('statusCode'));
    }
}
