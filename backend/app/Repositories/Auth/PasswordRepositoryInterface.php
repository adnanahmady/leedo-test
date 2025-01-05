<?php

namespace App\Repositories\Auth;

use App\Models\User;

interface PasswordRepositoryInterface
{
    public function find(string $email): ?User;

    public function reset(string $email, string $password): User;
}
