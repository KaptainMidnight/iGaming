<?php

namespace App\Http\Controllers\Authorization;

use App\Data\RegistrationData;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Route;

#[Prefix('api/v1')]
class AuthorizationController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    #[Route(methods: ['POST'], uri: '/register', name: 'register')]
    public function register(RegistrationData $registrationData)
    {
        $user = $this->userService->store($registrationData);

        return response()->json([
            'ok' => $user->wasRecentlyCreated,
            'token' => $user->createToken(config('app.name'))?->plainTextToken,
        ]);
    }
}
