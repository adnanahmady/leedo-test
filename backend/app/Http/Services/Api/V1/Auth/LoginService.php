<?php

namespace App\Http\Services\Api\V1\Auth;

use App\Dtos\Api\V1\Auth\LoggedInUserDto;
use App\Exceptions\InvalidCredentialsException;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginService
{
    public function __construct(private LoginRequest $request) {}

    public function login(): LoggedInUserDto
    {
        $credentials = $this->request->only(['email', 'password']);

        InvalidCredentialsException::throwIf(
            !Auth::attempt($credentials),
            ['email' => __('Email or password is wrong')]
        );

        return new LoggedInUserDto(
            user: $user = $this->request->user(),
            token: $user->createToken(config('app.key'))->plainTextToken,
        );
    }
}
