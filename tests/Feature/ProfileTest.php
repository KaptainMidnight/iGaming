<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\User;
use Database\Seeders\CurrencySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_profile_request(): void
    {
        $this->seed(CurrencySeeder::class);

        $user = User::factory()->create();
        $currency = Currency::query()->first();
        $user->wallets()->create([
            'currency_id' => $currency->id,
            'balance' => 1000,
        ]);

        Sanctum::actingAs($user);

        $this->getJson(route('profile'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'wallets' => [
                        '*' => [
                            'id',
                            'balance',
                            'currency',
                        ],
                    ],
                ],
            ])
            ->assertJsonFragment([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }

    public function test_failed_profile_request_for_unauthenticated_user(): void
    {
        $this->getJson(route('profile'))
            ->assertUnauthorized();
    }
}
