<?php

namespace App\Dtos\Api\V1\Auth;

use App\Models\User;

class LoggedInUserDto
{
    public function __construct(private User $user, private string $token) { }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
