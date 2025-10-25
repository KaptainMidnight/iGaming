<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\Confirmed;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Password;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class RegistrationData extends Data
{
    public function __construct(
        #[Required]
        public string $name,
        #[Email]
        #[Unique(table: 'users', column: 'email')]
        public string $email,
        #[Password]
        #[Confirmed]
        public string $password,
    ) {}
}
