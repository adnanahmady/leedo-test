<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterRepository implements RegisterRepositoryInterface
{
    public function register(string $email): User
    {
        return User::create(['email' => $email]);
    }

    public function activate(
        string $email,
        string $name,
        string $lastName,
        string $password
    ): User {
        $user = User::query()->where('email', $email)->first();

        $user->update([
            'name' => $name,
            'last_name' => $lastName,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        return $user;
    }
}
