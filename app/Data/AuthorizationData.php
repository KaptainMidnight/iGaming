<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Password;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class AuthorizationData extends Data
{
    public function __construct(
        #[Email]
        #[Required]
        public string $email,
        #[Required]
        #[Password]
        public string $password,
    ) {}
}
