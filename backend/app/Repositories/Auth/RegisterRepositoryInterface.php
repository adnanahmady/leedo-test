<?php

namespace App\Repositories\Auth;

use App\Models\User;

interface RegisterRepositoryInterface
{
    public function register(string $email): User;

    public function activate(
        string $email,
        string $name,
        string $lastName,
        string $password
    ): User;
}
