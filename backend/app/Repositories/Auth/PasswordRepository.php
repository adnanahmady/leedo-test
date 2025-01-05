<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordRepository implements PasswordRepositoryInterface
{
    public function find(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function reset(
        string $email,
        string $password
    ): User {
        $user = User::query()->where('email', $email)->first();

        $user->update(['password' => Hash::make($password)]);

        return $user;
    }
}
