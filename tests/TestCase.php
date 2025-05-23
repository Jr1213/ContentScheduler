<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase, WithFaker;

    protected User $loginUser;

    protected array $headers = [];

    public function setUp(): void
    {
        parent::setUp();
        $this->loginUser = User::factory()->create();

        $token = $this->loginUser->createToken('auth_token')->plainTextToken;
        $this->headers = [
            'Authorization' => 'Bearer ' . $token,
        ];
    }
}
