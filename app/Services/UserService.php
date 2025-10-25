<?php

namespace App\Services;

use App\Data\RegistrationData;
use App\Models\User;

final class UserService
{
    public function store(RegistrationData $registrationData): User
    {
        return User::query()->create($registrationData->toArray());
    }
}
