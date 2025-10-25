<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_authorization(): void
    {
        $password = 'password123123';
        $user = User::factory()->create([
            'password' => bcrypt($password),
        ]);

        $requestData = [
            'email' => $user->email,
            'password' => $password,
        ];

        $this->postJson(route('auth'), $requestData)
            ->assertOk()
            ->assertJsonStructure([
                'ok',
                'token',
            ])
            ->assertJsonFragment([
                'ok' => true,
            ]);
    }

    public function test_failed_authorization(): void
    {
        $requestData = [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ];

        $this->postJson(route('auth'), $requestData)
            ->assertOk()
            ->assertJsonStructure([
                'ok',
                'message',
            ])
            ->assertJsonFragment([
                'ok' => false,
            ]);
    }
}
