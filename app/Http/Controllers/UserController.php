<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthorizedUserResource;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Route;

#[Prefix('api/v1')]
class UserController extends Controller
{
    #[Route(methods: ['GET'], uri: '/profile', name: 'profile', middleware: ['auth:sanctum'])]
    public function profile(): AuthorizedUserResource
    {
        return new AuthorizedUserResource(auth()->user());
    }
}
