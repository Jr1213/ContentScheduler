<?php

namespace Tests\Feature\API;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function test_user_can_create_account(): void
    {
        $password = fake()->password(8);
        $data = [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'password' => $password,
            'password_confirmation' => $password,
        ];


        $response = $this->postJson(route('auth.register'), $data);

        $response->json();

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('users', ['name' => $data['name'], 'email' => $data['email']]);

        $this->assertArrayHasKey('token', $response['data']);

        $this->assertArrayHasKey('user', $response['data']);

        $this->assertArrayHasKey('name', $response['data']['user']);
    }

    public function test_user_can_login_to_account(): void
    {
        $user = User::factory()->create();

        $data = [
            'email' => $user->email,
            'password' => 'password',
        ];


        $response = $this->postJson(route('auth.login'), $data);
        
        $response->json();

        $response->assertStatus(Response::HTTP_OK);

        $this->assertArrayHasKey('token', $response['data']);

        $this->assertArrayHasKey('user', $response['data']);

        $this->assertArrayHasKey('name', $response['data']['user']);
    }

    public function test_user_must_insert_correct_credentials(): void
    {
        $user = User::factory()->create();

        $data = [
            'email' => $user->email,
            'password' => 'wrong-password',
        ];


        $response = $this->postJson(route('auth.login'), $data);
        
        $response->json();

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
