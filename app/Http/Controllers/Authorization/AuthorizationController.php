<?php

namespace App\Http\Controllers\Authorization;

use App\Data\AuthorizationData;
use App\Data\RegistrationData;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Route;

#[Prefix('api/v1')]
class AuthorizationController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    #[Route(methods: ['POST'], uri: '/register', name: 'register')]
    public function register(RegistrationData $registrationData): JsonResponse
    {
        $user = $this->userService->store($registrationData);

        return response()->json([
            'ok' => $user->wasRecentlyCreated,
            'token' => $user->createToken(config('app.name'))?->plainTextToken,
        ]);
    }

    #[Route(methods: ['POST'], uri: '/auth', name: 'auth')]
    public function authorization(AuthorizationData $authorizationData): JsonResponse
    {
        if (! auth()->attempt($authorizationData->toArray())) {
            return response()->json([
                'ok' => false,
                'message' => __('auth.failed'),
            ]);
        }

        $token = auth()->user()->createToken(config('app.name'))?->plainTextToken;

        return response()->json([
            'ok' => true,
            'token' => $token,
        ]);
    }
}
