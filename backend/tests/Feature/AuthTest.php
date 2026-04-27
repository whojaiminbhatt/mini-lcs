<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'role' => 'field_agent'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['success', 'message', 'data' => ['user', 'token']]);
        
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_user_can_login()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'message', 'data' => ['user', 'token']]);
    }

    public function test_user_can_get_me_details()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/auth/me');

        $response->assertStatus(200)
                 ->assertJsonPath('data.email', $user->email);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200);
        $this->assertCount(0, $user->tokens);
    }
}
