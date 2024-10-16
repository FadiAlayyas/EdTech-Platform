<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\User;
use App\Services\UserAuthService;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Mockery;

class UserAuthTest extends TestCase
{
    use RefreshDatabase;

    protected $userAuthServiceMock;
    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed(PermissionsSeeder::class);
        $this->seed(RolesSeeder::class);

        $this->userAuthServiceMock = Mockery::mock(UserAuthService::class);
        $this->app->instance(UserAuthService::class, $this->userAuthServiceMock);

        // Create a user for all tests
        $this->user = User::factory()->create();
        Auth::login($this->user);

        // Create a token for authentication
        $this->token = $this->user->createToken('Test Token')->plainTextToken;
    }

    /** @test */
    public function it_can_login_a_user()
    {
        $this->userAuthServiceMock->shouldReceive('login')
            ->once()
            ->with([
                'email' => $this->user->email,
                'password' => 'password123',
            ])
            ->andReturn($this->user);

        $response = $this->post('/api/auth/login', [
            'email' => $this->user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $this->assertEquals(200, $response->json('statusCode'));
    }

    /** @test */
    public function it_can_change_user_password()
    {
        $this->userAuthServiceMock->shouldReceive('changePassword')
            ->once()
            ->with([
                'old_password' => 'password123',
                'new_password' => 'newpassword',
                'new_password_confirmation' => 'newpassword',
            ])
            ->andReturn();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->post('/api/auth/change-password', [
            'old_password' => 'password123',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(200);
        $this->assertEquals(200, $response->json('statusCode'));
    }

    /** @test */
    public function it_can_fetch_user_profile_details()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get('/api/auth/profile-details');

        $response->assertStatus(200);
        $this->assertEquals(200, $response->json('statusCode'));
    }

    /** @test */
    public function it_can_logout_a_user()
    {
        $this->userAuthServiceMock->shouldReceive('logout')
            ->once()
            ->andReturn();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->post('/api/auth/logout');

        $response->assertStatus(200);
        $this->assertEquals(200, $response->json('statusCode'));
    }

    /** @test */
    public function it_can_update_user_profile()
    {
        $this->userAuthServiceMock->shouldReceive('profileUpdate')
            ->once()
            ->with([
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
                'phone_number' => '+963991833806',
            ])
            ->andReturn(true);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->put('/api/auth/profile-update', [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone_number' => '+963991833806',
        ]);

        $response->assertStatus(200);
        $this->assertEquals(200, $response->json('statusCode'));
    }
}
