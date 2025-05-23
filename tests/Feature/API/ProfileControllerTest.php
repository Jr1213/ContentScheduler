<?php

namespace Tests\Feature\API;

use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    public function test_login_user_can_fetch_profile(): void
    {
        $response = $this->getJson(route('profile.index'), $this->headers);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertArrayHasKey('user', $response['data']);

        $this->assertArrayHasKey('email', $response['data']['user']);


        $this->assertEquals($this->loginUser->email, $response['data']['user']['email']);
    }

    public function test_user_can_update_profile(): void
    {
        $data = [
            'name' => fake()->name(),
            'email' => fake()->safeEmail()
        ];


        $response = $this->putJson(route('profile.update'), $data, $this->headers);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('users', ['name' => $data['name'], 'email' => $data['email']]);

        $response->json();

        $this->assertArrayHasKey('user', $response['data']);

        $this->assertArrayHasKey('name', $response['data']['user']);
    }

    public function test_user_can_update_password(): void
    {
        $password = fake()->password(8);
        $data = [
            'password' => $password,
            'password_confirmation' => $password,
            'current_password' => 'password',
        ];


        $response = $this->putJson(route('profile.update-password'), $data, $this->headers);

        $response->assertStatus(Response::HTTP_OK);

        $login = $this->postJson(route('auth.login'), ['email' => $this->loginUser->email, 'password' => $password]);

        $login->assertStatus(Response::HTTP_OK);
    }

    public function test_user_must_insert_correct_current_password_when_update_password(): void
    {
        $password = fake()->password(8);
        $data = [
            'password' => $password,
            'password_confirmation' => $password,
            'current_password' => 'wrong-password',
        ];


        $response = $this->putJson(route('profile.update-password'), $data, $this->headers);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_user_can_delete_account(): void
    {
        $response = $this->deleteJson(route('profile.destroy'), ['current_password' => 'password'], $this->headers);

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('users', [
            'id' => $this->loginUser->id,
            'deleted_at' => null
        ]);
    }

    public function test_user_must_insert_correct_password_before_delete_account(): void
    {
        $response = $this->deleteJson(route('profile.destroy'), ['current_password' => 'wrong-password'], $this->headers);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
