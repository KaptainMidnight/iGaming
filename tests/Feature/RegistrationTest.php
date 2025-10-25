<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_registration(): void
    {
        $password = 'password123123';
        $requestData = [
            'name' => fake()->firstName,
            'email' => 'test@example.com',
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $this->postJson(route('register'), $requestData)
            ->assertOk()
            ->assertJsonStructure([
                'ok',
                'token',
            ])
            ->assertJsonFragment([
                'ok' => true,
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $requestData['email'],
        ]);
    }

    public function test_registration_fails_if_email_is_not_unique(): void
    {
        $user = User::factory()->create();
        $password = 'password123123';
        $requestData = [
            'email' => $user->email,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $this->postJson(route('register'), $requestData)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    }

    public function test_registration_fails_if_password_is_not_confirmed(): void
    {
        $requestData = [
            'email' => 'test@example.com',
            'password' => 'password123123',
            'password_confirmation' => 'wrong-password',
        ];

        $this->postJson(route('register'), $requestData)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('password');
    }

    public function test_registration_fails_if_email_is_invalid(): void
    {
        $requestData = [
            'email' => 'not-an-email',
            'password' => 'password123123',
            'password_confirmation' => 'password123123',
        ];

        $this->postJson(route('register'), $requestData)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    }
}
