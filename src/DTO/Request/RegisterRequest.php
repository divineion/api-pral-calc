<?php

namespace App\DTO\Request;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'Email is required')]
        #[Assert\Email(message: 'Invalid email format')]
        public readonly string $email = '',

        #[Assert\NotBlank(message: 'Username is required')]
        #[Assert\Length(min: 3, max: 20, minMessage: 'Username must be at least 3 characters', maxMessage: 'Username must be at most 20 characters')]
        public readonly string $username = '',

        #[Assert\NotBlank(message: 'Password is required')]
        #[Assert\Length(min: 8, minMessage: 'Password must be at least 8 characters')]
        public readonly string $password = '',
    ) {}
}
